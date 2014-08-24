<?php
// how many pages we have when using paging?
$maxPage = ceil($numrows/$rowsPerPage);
// print the link to access each page
$nav  = '';
for($page = 1; $page <= $maxPage; $page++) {
   if ($page == $pageNum) {
      $nav .= " $page ";
   } else {
      $nav .= " <a href=\"index.php?site=ladderinfo&ladderID=$ladderID&action=ranking&page=$page\">$page</a> ";
   }
}
// print nav link
eval ("\$pages = \"".gettemplate("pages")."\";");
echo $pages;
?>