<?

echo '<pre>';
print_r( $this->session->all_userdata() );
echo '</pre>';

?>
<a href='<?= base_url()."main/logout" ?>'>Logout</a>