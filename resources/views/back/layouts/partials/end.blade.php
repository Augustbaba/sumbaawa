<!-- content-footer -->
    <footer class="content-footer">
        <div>© {{ date('Y') }} - <a href="" target="_blank">{{ FrontHelper::getAppName() }}</a></div>
        <div>
            <nav class="nav gap-4">
                <a href="" class="nav-link">Licenses</a>
                <a href="#" class="nav-link">Change Log</a>
                <a href="#" class="nav-link">Get Help</a>
            </nav>
        </div>
    </footer>
    <!-- ./ content-footer -->

</div>
<!-- ./ layout-wrapper -->
@yield('scripts')
<!-- Bundle scripts -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/libs/bundle.js') }}"></script>

<!-- Apex chart -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/libs/charts/apex/apexcharts.min.js') }}"></script>

<!-- Slick -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/libs/slick/slick.min.js') }}"></script>

<!-- Examples -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/dist/js/examples/dashboard.js') }}"></script>

<!-- Main Javascript file -->
<script src="{{ asset(FrontHelper::getEnvFolder() . 'storage/back/dist/js/app.min.js') }}"></script>
</body>

</html>
