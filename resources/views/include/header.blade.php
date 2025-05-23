<header class="header-top" id="header-top" header-theme="light">
   <div class="container-fluid">
      <?php $user_flag = Session::get('user_flag');?>
      <input type="hidden" id="user_flag" name="" value='<?php echo $user_flag; ?>'>
      <div class="d-flex justify-content-between">
         <div class="top-menu d-flex align-items-center">
            <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
         </div>
         <div class="top-menu d-flex align-items-center">
            <div class="dropdown">
               <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="avatar" src="{{ asset('img/avatar.png')}}" alt=""><span class="hidden-xs">
               <span> {{ Session::get('companyname') }}</span></a>
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="{{ url('logout') }}">
                  <i class="ik ik-power dropdown-icon"></i> 
                  {{ __('Logout')}}
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</header>