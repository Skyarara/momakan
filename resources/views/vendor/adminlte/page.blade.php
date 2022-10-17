<style>
    .no-js #loader {
        display: none;
    }

    .js #loader {
        display: block;
        position: absolute;
        left: 100px;
        top: 0;
    }

    .se-pre-con {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url("<?php echo e(url('/gif/' . 'Preloader_3.gif')); ?>") center no-repeat #fff;
    }
</style>
<?php $__env->startSection('adminlte_css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'red') . '.min.css')); ?> ">
<?php echo $__env->yieldPushContent('css'); ?>
<?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'skin-' . config('adminlte.skin', 'red') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '')); ?>

<?php $__env->startSection('body'); ?>
<div class="se-pre-con"></div>
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <?php if (config('adminlte.layout') == 'top-nav') : ?>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="navbar-brand">
                            <?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?>

                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <?php echo $__env->renderEach('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item'); ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                <?php else : ?>
                    <!-- Logo -->
                    <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="logo">
                        <!-- mini logo for sidebar mini 50x50 pixels -->
                        <span class="logo-mini"><?php echo config('adminlte.logo_mini', '<b>A</b>LT'); ?></span>
                        <!-- logo for regular state and mobile devices -->
                        <span class="logo-lg"><?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?></span>
                    </a>

                    <!-- Header Navbar -->
                    <nav class="navbar navbar-static-top" role="navigation">
                        <!-- Sidebar toggle button-->
                        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                            <span class="sr-only"><?php echo e(trans('adminlte::adminlte.toggle_navigation')); ?></span>
                        </a>
                    <?php endif; ?>
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">

                        <ul class="nav navbar-nav">
                            <li>
                                <?php if (config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<')) : ?>
                                    <a href="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>">
                                        <i class="fa fa-fw fa-power-off"></i> <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                    </a>
                                <?php else : ?>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-fw fa-power-off"></i> <?php echo e(trans('adminlte::adminlte.log_out')); ?>

                                    </a>
                                    <form id="logout-form" action="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>" method="POST" style="display: none;">
                                        <?php if (config('adminlte.logout_method')) : ?>
                                            <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                                        <?php endif; ?>
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <?php if (config('adminlte.layout') == 'top-nav') : ?>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <?php if (config('adminlte.layout') != 'top-nav') : ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php if (auth()->user()->role_id == 1) : ?>
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="https://cdn1.iconfinder.com/data/icons/big-rocket/80/BigRocket-1-02-512.png" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo e(Auth::user()->name); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <?php echo $__env->renderEach('adminlte::partials.menu-item', $adminlte->menu(), 'item'); ?>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
        <?php elseif (auth()->user()->role_id == 3) : ?>
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="https://cdn1.iconfinder.com/data/icons/big-rocket/80/BigRocket-1-02-512.png" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo e(Auth::user()->corporate->name); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <?php echo $__env->renderEach('adminlte::partials.menu-item', config('adminlte.ganti'), 'item'); ?>
                        <li class="treeview">
                                <a href="#">
                                  <i class="fa fa-user"></i> <span>Akun</span>
                                  <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                  </span>
                                </a>
                                <ul class="treeview-menu" style="display: none;">
                                  <li><a href="/profile/<?php echo e(Auth::user()->id); ?>"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                                  <li><a href="/profile/password/<?php echo e(Auth::user()->id); ?>"><i class="fa fa-lock"></i>Password</a></li>
                                </ul>
                              </li>
                        <li><a href="/employee/data/<?php echo e(Auth::user()->corporate->id); ?>"><i class="fa fa-users"></i> <span>Pegawai</span></a></li>
                        <li><a href="/kontrak"><i class="fa fa-ils"></i> <span>Kontrak</span></a></li>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>


        <?php elseif (auth()->user()->role_id == 4) : ?>
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="https://cdn1.iconfinder.com/data/icons/big-rocket/80/BigRocket-1-02-512.png" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?php echo e(Auth::user()->name); ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <?php echo $__env->renderEach('adminlte::partials.menu-item', config('adminlte.ven'), 'item'); ?>
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
        <?php endif; ?>
    <?php endif; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php if (config('adminlte.layout') == 'top-nav') : ?>
            <div class="container">
            <?php endif; ?>

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <?php echo $__env->yieldContent('content_header'); ?>
            </section>

            <!-- Main content -->
            <section class="content">

                <?php echo $__env->yieldContent('content'); ?>

            </section>
            <!-- /.content -->
            <?php if (config('adminlte.layout') == 'top-nav') : ?>
            </div>
            <!-- /.container -->
        <?php endif; ?>
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->
<?php $__env->stopSection(); ?>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script>
    $(window).on('load', function() {
        $(".se-pre-con").fadeOut("slow");
    });
</script>
<?php $__env->startSection('adminlte_js'); ?>
<script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('js'); ?>
<?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::master', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>