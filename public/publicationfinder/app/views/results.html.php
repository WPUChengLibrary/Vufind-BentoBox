<?php 
/**
 * Results view
 * LOCATION: http://widgets.ebscohost.com/prod/ftf-atoz/app/views/results.html.php
 * APP NAME: Publication Finder API Sample
 **/

$queryStringUrl = $results['queryString'];
// URL used by facets links
$refineParams = array(
    'refine' => 'y',
    'query'  => $searchTerm,
    'fieldcode' => $fieldCode
);
$refineParams = http_build_query($refineParams);
$refineSearchUrl = "results.php?".$refineParams;
$encodedSearchTerm = http_build_query(array('query'=>$searchTerm));
$encodedHighLigtTerm = http_build_query(array('highlight'=>$searchTerm));
?>

<!--START: copied from basic_search.html -->

<script type="text/javascript">
      function submitForm(letter) {
          $('#spinner').show();
          var form = document.searchLetter;
          form.query.value = "JN " + letter + "*";
          form.submit();
      }
      function submitTerm() {
          var form1 = document.searchLetter;
          var form2 = document.searchTerm;
          var term = form2.sTerm.value;
          if (term == "") {
              $('.messageST').show();
          } else {
              $('#spinner').show();
              var pre;
              if (form2.prefix[1].checked) pre = 'SO';
              else pre = 'JN';
              form1.query.value = pre + " " + term + "*";
              form1.submit();
          }
      }
      function showSubletterMenu(currentLetter) {
          var subletterMenu = '<ul><li class="subletter"><a href="#" style="margin-right: 10px;" onclick="javascript:submitForm(\'' + currentLetter + '\')">' + currentLetter + '</a></li>';
          var letters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
          var size = letters.length;
          for (var i = 0; i < size; ++i) {
              subletterMenu += '<li class="subletter"><a href="#" onclick="javascript:submitForm(\'' + currentLetter + letters[i] + '\')">' + currentLetter + letters[i] + '</a></li>';
          }
          subletterMenu += '</ul>';
          $('#letter-submenu').html(subletterMenu);
      }
      $(document).ready(function () {
          $('#submit').click(function () {
              var form1 = document.searchLetter;
              var form2 = document.searchTerm;
              var term = form2.sTerm.value;
              if (term == "") {
                  $('.messageST').show();
              } else {
                  $('#spinner').show();
                  var pre;
                  if (form2.prefix[1].checked) pre = 'SO';
                  else pre = 'JN';
                  form1.query.value = pre + " " + term + "*";
                  form1.submit();
              }
          });
          $('.searchBox').keypress(function (e) {
              if (e.which == 13) {
                  submitTerm();
                  return false;
              }
          });
      });


		</script>

<div id="spinner" class="spinner" style="display:none;">
			<img id="img-spinner" src="web/spinner.gif" alt="Loading"/>
		</div>


<div class="">
<?php if ($debug=='y') {?>
    <div style="float:right"><a target="_blank" href="debug.php?result=y">Search response XML</a></div>
    <?php   
} 
    ?>
<div class="">
<?php if ($error) { ?>
    <div class="error">
        <?php echo $error; ?>
    </div>
    <?php 
} 
    ?>



<div class="">
    <?php if (empty($results['records'])) { ?>
            <div class="pull-left help-block">
                <h3>No Results!</h3>
              <p class="alert alert-danger">
          Your search - <strong><?php echo $searchTerm ?></strong> - did not match any resources.      </p>
            </div>

    <?php 
} else { 
    ?> 

    <?php $i = 0;
    foreach ($results['records'] as $result) { 
	 if(array_search("Ebrary Academic Complete", array_column($result['FullTextHoldings'],'Name')) !== 0 ){	 
  if(++$i > 5)
    break;
	        ?>
            <div class="row result clearfix">
                <label class="pull-left">
                    <?php echo $i; ?>
                </label>                     
                <div class="listentry col-xs-11">
                    <div class="resultItemLine1">
        <?php 
        if ((!isset($_COOKIE['login']))&&$result['AccessLevel']==1) { ?>
                            <p>This record from <b>[<?php echo $result['DbLabel'] ?>]</b> cannot be displayed.<a href="login.php?path=results&<?php echo $encodedSearchTerm;?>&fieldcode=<?php echo $fieldCode; ?>">Login</a> for full access.</p>
		<?php 
        } else {  
            ?>
			<?php 
            if (!empty($result['PLink'])&&(!empty($result['Items']['Ti']))) {
                 ?>  
<style>
a:link {
    text-decoration: none;
}
a:visited {
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
a:active {
    text-decoration: underline;
}
</style>

							         <a target="_blank" class="title _record_link" href="<?php echo $result['PLink'].'&authtype=cookie,uid&scope=site'; ?>"><?php echo $result['Items']['Ti']['Data']; ?> </a>
							         <div class="resultItemLine1">
            <?php 
            if (!empty($result['Items']['ISSN']['Data'])) echo 'ISSN: '.$result['Items']['ISSN']['Data'].'. <br>';
                if (!empty($result['FullTextHoldings'])) {                                              
                    foreach ($result['FullTextHoldings'] as $fullTextHolding) {?>
                    <a class="custom-link" target="_blank" href="<?php echo $fullTextHolding['URL']?>"><?php echo $fullTextHolding['Name']?></a>:
                       <?php if (!empty($fullTextHolding['CoverageDates'])) echo ' '.$fullTextHolding['CoverageDates'].'. ';
                        if (!empty($fullTextHolding['Embargo'])) echo ' '.$fullTextHolding['Embargo'];
                        echo '<br>';
                    }
                } 
            
                ?>
							           </div>
			<?php 
            } ?>
		<?php 
        } ?>
					   </div>
                </div>
            </div>
    <?php 
    } ?>
    <?php 
} }?>
</div>

        </div>
    </div>

