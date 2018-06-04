<?php
include 'head.php';
//ini_set('display_errors','0');
?>

<!--  wrapper (footerda bitir) -->
<div id="wrapper">
    <!-- navbar top -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation" id="navbar">
        <!-- navbar-header -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <spam style="color: white; font-size: 40px; font-weight: bold;">OnlineQuiz</spam>
            </a>
        </div>
        <!-- end navbar-header -->

        <!-- navbar-top-links -->
        <!--   user     -->
        <ul class="nav navbar-top-links navbar-right">
            <!-- main dropdown -->
            <li class="dropdown" title="Log Out">
                <a href="logout.php">
                    <i class="fa fa-sign-out fa-3x"></i>
                </a>
                <!-- end dropdown-user -->
            </li>
            <!-- end main dropdown -->
        </ul>
        <!-- end navbar-top-links -->

    </nav>
    <!-- end navbar top -->
