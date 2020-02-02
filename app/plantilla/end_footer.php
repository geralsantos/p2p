


  
<script>

</script>
  <?php foreach (glob(JS_ROOT . DS . 'componentes' . DS ."portada/*.js") as $filename): ?>
  <script type="text/javascript" src="<?php echo JS . '/componentes/portada/'. basename($filename); ?>"></script>
<?php endforeach;?>

    <!-- Highchart JS
		============================================ -->

      <script src="<?php echo JS ?>/highcharts/highcharts.js"></script>
  <script src="<?php echo JS ?>/highcharts/data.min.js"></script>
  <script src="<?php echo JS ?>/highcharts/exporting.js"></script>
  <script src="<?php echo JS ?>/highcharts/export-data.js"></script>
  <script src="<?php echo JS ?>/highcharts/drilldown.js"></script>
  <script src="<?php echo JS ?>/highcharts/histogram-bellcurve.js"></script>

    <!-- jquery
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/vendor/jquery-1.12.4.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="<?php echo ASSETS ?>/js/scrollbar/mCustomScrollbar-active.js"></script>

    <!-- sparkline JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo ASSETS ?>/js/sparkline/jquery.charts-sparkline.js"></script>
    <!-- calendar JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/calendar/moment.min.js"></script>
    <script src="<?php echo ASSETS ?>/js/calendar/fullcalendar.min.js"></script>
    <script src="<?php echo ASSETS ?>/js/calendar/fullcalendar-active.js"></script>
	<!-- float JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/flot/jquery.flot.js"></script>
    <script src="<?php echo ASSETS ?>/js/flot/jquery.flot.resize.js"></script>
    <script src="<?php echo ASSETS ?>/js/flot/curvedLines.js"></script>
    <script src="<?php echo ASSETS ?>/js/flot/flot-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/plugins.js"></script>
    <!-- main JS
		============================================ -->
    <script src="<?php echo ASSETS ?>/js/main.js"></script>

    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.<?php echo ASSETS ?>/js/2.7.3/Chart.js"></script-->


    <script type="text/javascript" src="<?php echo JS ?>/vue/app.js"></script>
    <script type="text/javascript" src="<?php echo JS ?>/vue/app_login.js"></script>
    <!-- metisMenu JS
    ============================================ -->
    <script src="<?php echo ASSETS ?>/js/metisMenu/metisMenu.min.js"></script>
    <script src="<?php echo ASSETS ?>/js/metisMenu/metisMenu-active.js"></script>
    <script src="<?php echo JS ?>/bracket/jquery.bracket.min.js"></script>
    <script src="<?php echo JS ?>/swalExtend.js"></script>



<script type="text/javascript">
if ("<?php echo $_SESSION["mensaje_alerta"]["tipo"] ?>"=="error") {
  swal("Error", "<?php echo $_SESSION["mensaje_alerta"]["texto"] ?>", "warning");
  <?php unset($_SESSION['mensaje_alerta']);unset($_SESSION['mensaje_alerta']) ?>
}

</script>
<footer>
<div class="footer-text"> <p>2019 © PULPO MASTER & Developed by <a style="text-decoration: underline;" href="mailto:lgsoftware.gestion@gmail.com">LGsoftware</a></p>
  </div>
</footer>

</body>

</html>
