<?php
class Dom {
public $form;
public $input_value_inject;
public $form_action_url;
public $Err;
public $css;
#       = $_SERVER['HTTP_REFERER'];                                                             public $post_data_inputs;
public $input_flag = "FALSE";
public $nav_bar_links;
public $nav_page_locations = array('Pending' => 'pending_invoice.php',
	'Billing' => 'index1.php',
	'Income' => 'income_statement.php',
	'account ledger' => 'account_ledger.php');
###############################################################################
public function nav_links(){
        $this->css .= <<<EOT
.dropdown{
 position: relative;
 display: inline-block;
}
.dropdown-content{
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 160px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
padding: 12px 16px;
z-index: 1;
}
.dropdown:hover .dropdown-content{
 display: block;
}
EOT;
	$this->nav_html .= '<div class="dropdown">';
	$this->nav_html .= $this->tag_wrap('span','Navigation');
        $this->nav_html .= '<div class="dropdown-content">';
        foreach($this->Nav_links as $key => $value){
		$this->nav_html .= $this->tag_wrap('ul',
			$this->link_url_key_id_wrap($value,
		       	'' ,
		       	'' ,
		      	$key) );
        }
        $this->nav_html .= '</div>';
        $this->nav_html .= '</div>';
}
##############################################################################
###############################################################################                 
public function nav_links_get($menue_name){
	$this->css .= <<<EOT
.dropdown{
 position: relative;
 display: inline-block;
}
.dropdown-content{
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 160px;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
padding: 12px 16px;
z-index: 1;
}
.dropdown:hover .dropdown-content{
display: block;
}
EOT;
        $this->nav_html .= '<div class="dropdown">';
        $this->nav_html .= $this->tag_class_wrap('span', 'class="dropbtn" ', $menue_name);
        $this->nav_html .= '<div class="dropdown-content">';
        foreach($this->Nav_links as $key => $value){
                $this->nav_html .= $this->tag_wrap('ul',$this->link_wrap($value, $key) );
        }
        $this->nav_html .= '</div>';
        $this->nav_html .= '</div>';
}
##############################################################################
###############################################################################
public function nav_bar_drop_links_get($menue_name, $links_array){
        $link = '';
        $this->css .= <<<EOT
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li a, .dropbtn {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}
}

ul span, .dropbtn {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}
li a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

li.dropdown {
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {background-color: #f1f1f1;}                                          
.dropdown:hover .dropdown-content {
  display: block;
}
EOT;
        $button = $this->tag_class_wrap('a', 'href="javascript:void(0)" class="dropbtn"', $menue_name);
        foreach($links_array as $key => $value){
                $link .= $this->link_wrap($value, $key);
	}
	$dropdown = $this->tag_class_wrap('div', 'class="dropdown-content"', $link);
	$button .= $dropdown;
	$button = $this->tag_class_wrap('li', 'class="dropdown"', $button);
        $this->nav_bar_links .= $button;
}
##############################################################################
###############################################################################
public function nav_bar_links($url, $id){
                $this->nav_bar_links .= $this->tag_wrap('li', $this->link_wrap($url, $id));
}
##############################################################################
###########################################################
public function tag_class_wrap($tag, $class, $wrap){
        $html = <<<EOT
                <$tag $class>$wrap</$tag>
EOT;
        return $html;
        }
#########################################################
###########################################################
public function tag_wrap($tag, $wrap){
        $html = <<<EOT
                <$tag>$wrap</$tag>
EOT;
        return $html;
        }
#########################################################
public function input_type_name_value($type, $name, $value){
        $input = <<<EOT
                <input type="$type" name="$name" value="$value">
EOT;
    return $input;
}
############################################################
public function test_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
}
############################################################
############################################################
public function link_wrap($url, $wrap){
        $link = <<<EOT
        <a href="$url">$wrap</a>
EOT;
        return $link;
}
###########################################################
############################################################
public function link_url_key_id_wrap($url, $key, $id,$wrap){
        $link = <<<EOT
        <a href="$url?$key=$id">$wrap</a>
EOT;
        return $link;
}
###########################################################
public function link_url_id_wrap($url,$id,$wrap){
        $link = <<<EOT
        <a href="$url?id=$id">$wrap</a>
EOT;
        return $link;
}
###########################################################
#
#
public function post_inputs_test($form_inputs){
    $data_Err = "TRUE";
	foreach($form_inputs as $x_value => $x){
		if ($x != 'hidden'){
		if (empty($_POST[$x_value])) {
		$this->Err[$x_value] = $x_value . " is required <br>";
		$data_Err = "FALSE";
        	} else { $insert[$x_value] = $this->test_input($_POST[$x_value]); }
		}
	$insert[$x_value] = $this->test_input($_POST[$x_value]);
	}
	if($data_Err == "TRUE" ){
        //insert_new_entry($insert);
	$this->post_data_inputs = $insert;
	$this->input_flag = $data_Err;
	}
}
############################################################
#
public function array_inputs($form_inputs){
    $wrap = '';
#    print_r($this->input_value_inject[0]);
foreach($form_inputs as $x_value => $x){
	$value = (isset($this->input_value_inject[0][$x_value]) ? $this->input_value_inject[0][$x_value] : '' );
	$wrap .= $this->input_type_name_value_err($x,
	       	$x_value,
	       	((isset($_POST[$x_value]) ? $_POST[$x_value] : $value )),
	       	((isset($this->Err[$x_value]) ? $this->Err[$x_value] : '' )));
        }
    return $wrap;
}
#
############################################################
#$this->form_new_list($array($tag = $type)) returns $this->form = $form;
# Data flag           $this->post_data_inputs = $insert;
# Error flag array    $this->input_flag = $data_Err;
#############################################################
public function form_new_list($form_inputs){
    $inputs = '';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$this->post_inputs_test($form_inputs);
        }
        $action = $this->form_action_url;
	$wrap = $this->array_inputs($form_inputs);
        $form = $this->form_action_wrap_post($action, $wrap);
        $this->form = $form;

//if($data_Err == "TRUE" ){
# insert_new_list($product, $price, $labor, $trade, $type);
//}
}
#########################################################################
#########################################################################
# Form action = URL     $action?id=$id  wrap inputs $wrap
#########################################################################
public function form_action_id_wrap_get($action, $wrap ){
        $form = <<<EOT
                <form action="$action" method="get">
                        $wrap
                        <input type="submit" name="submit" value ="submit">
                </form>
EOT;
return $form;
}
###################################################################
#Form action = URL     $action?id=$id  wrap inputs $wrap
#################################################################
public function form_action_wrap_post($action, $wrap){
        $form = <<<EOT
                <form action="$action" method="post">
                        $wrap
                        <input type="submit" name="submit" value ="submit">
                </form>
EOT;
return $form;
}
#################################################################
##################################################################
# Form action = URL     $action?id=$id  wrap inputs $wrap
#########################################################################
public function form_action_id_wrap_post($action, $id, $wrap, $index){
        $form = <<<EOT
                <form action="$action?$index=$id" method="post">
                        $wrap
                        <input type="submit" name="submit" value ="submit">
                </form>
EOT;
return $form;
}
#################################################################
## test a string and returns the string
##############################################################
public function test_input2($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
}
##################################################################
#########################################################################
## input  type = text hidden date name = $name   value = $value
#########################################################################
public function input_type_name_value_err($type, $name, $value, $Err){
        $input = <<<EOT
                <span class="error">* $Err </span>
                $name : <input type="$type" name="$name" value = "$value"><br>
EOT;
return $input;
}
#######################################################################
public function array_table_dump($array = array(array(' '))){
	$tr = '';
	$td = '';
        foreach($array as $row => $entry){
            foreach($entry as $debit => $value){
		    $td .= $this->tag_wrap('td', $value);
		}
		$tr .= $this->tag_wrap('tr', $td);
		$td ='';
        }
        return $this->tag_class_wrap('table', 'style="width:100%"', $tr);
}
#########################################################################
#######################################################################
public function array_table_dump_balance($array = array(array())){
	$Balance = 0;
	foreach($array as $row => $entry){
            foreach($entry as $debit => $value){
		    ($debit == "Amount" ? $Balance += $value : '');
	    }
        }
        return $Balance;
}
#########################################################################
public function t_account_template($t_account_dump){
	$account_html ='';
	foreach($t_account_dump as $account => $value){
    		$account_html .= $this->tag_wrap('tr',
		$this->tag_class_wrap('th',
		'colspan="2" style="text-align: center;"',
		$account));
		$account_html .= $this->tag_wrap('tr',
			$this->tag_class_wrap('th',
		       	'colspan="1"',
			'Debit') . $this->tag_class_wrap('th',
			'colspan="1"',
		       	'Credit'));
        $output = $this->tag_wrap('pre', var_export($value, true));
        $td = (isset($value['Debit']) ? $this->array_table_dump($value['Debit']) : $this->array_table_dump());
        $balance = (isset($value['Debit']) ? $this->array_table_dump_balance($value['Debit']) : 0 );
        $td = $this->tag_wrap('td', $td);
        $tc = (isset($value['Credit']) ? $this->array_table_dump($value['Credit']) : $this->array_table_dump());
        $balance -= (isset($value['Credit']) ? $this->array_table_dump_balance($value['Credit']) : 0 );
        $td .= $this->tag_wrap('td', $tc);
        $account_html .= $this->tag_wrap('tr', $td);
        $account_html .= $this->tag_wrap('tr', $this->tag_wrap('td', 'Balance') . $this->tag_wrap('td', $balance));
        $balance = 0;                                                                                   }
 return $this->tag_class_wrap('table', 'style="width:100%"', $account_html);
}
#########################################################################
#######################################################################
public function mark_up($price, $markup){
        $marked = $price * $markup;
        return number_format($marked , 2);
}
#########################################################################














}
