<?php
### Ast_comments_search_reset.php
### 
### Special thanks to Yiannos Katsirintakis <janokary@gmail.com> for the base code.    www.publicissue.gr LICENSE: GPLv2
#### Copyright (C) 2017  Noel Cruz <noel@mycallcloud.com> MyCallCloud LLC    LICENSE: AGPLv2
#
# Reset Search Page
# created 01-06-2017 noel cruz noel@mycallcloud.com

session_start();

session_destroy();
?>
<html>

<meta http-equiv='refresh' content='0;URL=AST_comments_reports.php' />


</html>