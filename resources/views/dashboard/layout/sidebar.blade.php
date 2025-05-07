 <!-- Sidebar Start -->
 <aside class="left-sidebar">
     <!-- Sidebar scroll-->
     <div>
         <div class="brand-logo d-flex align-items-center justify-content-between">
             <a href="/" class="text-nowrap logo-img">
                 <img src="{{ asset('asset/img/logo.jpg') }}" width="50" alt="" /> Analisis Sentimen
             </a>
             <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                 <i class="ti ti-x fs-8"></i>
             </div>
         </div>
         <!-- Sidebar navigation-->
         <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
             <ul id="sidebarnav">
                 <li class="nav-small-cap">
                     <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                     <span class="hide-menu">Fitur Utama</span>
                 </li>
                 <li class="sidebar-item {{ Request::is('/dashboard') ? 'active' : '' }}">
                     <a class="sidebar-link " href="/dashboard" aria-expanded="false">
                         <span>
                             <i class="fa-solid fa-gauge"></i>
                         </span>
                         <span class="hide-menu">Dashboard</span>
                     </a>

                 </li>

                 <li class="sidebar-item {{ Request::is('/dashboard/tweets') ? 'active' : '' }}">
                     <a class="sidebar-link" href="/dashboard/tweets" aria-expanded="false">
                         <span>
                             <i class="fa-brands fa-twitter"></i>
                         </span>
                         <span class="hide-menu">Tweets</span>
                     </a>
                 </li>
                 <li class="sidebar-item {{ Request::is('/dashboard/process') ? 'active' : '' }}">
                     <a class="sidebar-link" href="/dashboard/process" aria-expanded="false">
                         <span>
                             <i class="fa-solid fa-gears"></i>
                         </span>
                         <span class="hide-menu">Preprocessing</span>
                     </a>
                 </li>
                 <li class="sidebar-item {{ Request::is('/dashboard/sentiment') ? 'active' : '' }}">
                     <a class="sidebar-link" href="/dashboard/sentiment" aria-expanded="false">
                         <span>
                             <i class="fa-solid fa-comments-question-check"></i>
                         </span>
                         <span class="hide-menu">Sentiment</span>
                     </a>
                 </li>
                 <li class="nav-small-cap">
                     <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                     <span class="hide-menu">Data Master</span>
                 </li>
                 <li class="sidebar-item {{ Request::is('/dashboard/lexicon') ? 'active' : '' }}">
                     <a class="sidebar-link" href="/dashboard/lexicon" aria-expanded="false">
                         <span>
                             <i class="ti ti-server"></i>
                         </span>
                         <span class="hide-menu">Lexicon Datas</span>
                     </a>
                 </li>
                 <li class="sidebar-item {{ Request::is('/dashboard/users') ? 'active' : '' }}">
                     <a class="sidebar-link" href="/dashboard/users" aria-expanded="false">
                         <span>
                             <i class="ti ti-user-plus"></i>
                         </span>
                         <span class="hide-menu">Users</span>
                     </a>
                 </li>

             </ul>
         </nav>
         <!-- End Sidebar navigation -->
     </div>
     <!-- End Sidebar scroll-->
 </aside>
 <!--  Sidebar End -->
