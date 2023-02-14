<style>
    * {
    box-sizing: border-box;
    }

    /* Create three equal columns that floats next to each other */
    .column {
        float: left;
        width: 25%;
        padding: 10px;
        
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>

<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
        <a href="?page=woowib-setting" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Settings</a>
        <!-- <a href="?page=woowib-setting&tab=settings" class="nav-tab <?php /** if($tab==='settings'):?>nav-tab-active<?php endif; */ ?>">Settings</a>
        -->
    </nav>

    <div class="tab-content">
        <?php 
            switch($tab) :
                case 'settings':                
                break;
                case 'tools':
                break;
                default:
                  include(WOOWIB_TEMPLATE_ADMIN.'tab-setting/html-tab-setting.php');
                break;
            endswitch; 
        ?>
    </div>
</div>