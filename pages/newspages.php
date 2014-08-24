<?php
// how many pages we have when using paging?
$maxPage = ceil($numrows/$rowsPerPage);
// print the link to access each page
$nav  = '';
for($page = 1; $page <= $maxPage; $page++) {
   if ($page == $pageNum) {
      $nav .= " $page "; // no need to create a link to current page
   } else {
      $nav .= " <a href=\"index.php?site=news&page=$page\">$page</a> ";
   }
}
// print the navigation link
eval ("\$pages = \"".gettemplate("pages")."\";");
echo $pages;
?>