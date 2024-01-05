<?php  

function is_logged_in()
{
    $ci = get_instance();
    if(!$ci->session->userdata('email'))
    {
        redirect('auth');
    }else {
        $role_id = $ci->session->userdata('role_id');
        $menu = $ci->uri->segment(1); //untuk mengambil segment ke 1 dari url

        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array(); //untuk mengambil data dari database

        $menu_id = $queryMenu['id']; //untuk mengambil id dari database

        $userAccess = $ci->db->get_where('user_access_menu', [
            'role_id' => $role_id, 
            'menu_id' => $menu_id
        ]);

        if($userAccess->num_rows() <1)
        {
            redirect('auth/blocked');
        }
    }    
}


function check_access($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id); //untuk mengambil data role_id dari database
    $ci->db->where('menu_id', $menu_id); //untuk mengambil data menu_id dari database
    $result = $ci->db->get('user_access_menu'); //untuk mengambil data dari database

    if($result->num_rows() > 0)
    {
        return "checked='checked'";
    }
}