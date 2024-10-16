$(document).ready(function () {
  $("#showHidePassword a").on("click", function (event) {
    event.preventDefault();
    if ($("#showHidePassword input").attr("type") == "text") {
      $("#showHidePassword input").attr("type", "password");
      $("#showHidePassword i").addClass("fa-eye-slash");
      $("#showHidePassword i").removeClass("fa-eye");
    } else if ($("#showHidePassword input").attr("type") == "password") {
      $("#showHidePassword input").attr("type", "text");
      $("#showHidePassword i").removeClass("fa-eye-slash");
      $("#showHidePassword i").addClass("fa-eye");
    }
  });
  $("#showHideConfirmPassword a").on("click", function (event) {
    event.preventDefault();
    if ($("#showHideConfirmPassword input").attr("type") == "text") {
      $("#showHideConfirmPassword input").attr("type", "password");
      $("#showHideConfirmPassword i").addClass("fa-eye-slash");
      $("#showHideConfirmPassword i").removeClass("fa-eye");
    } else if ($("#showHideConfirmPassword input").attr("type") == "password") {
      $("#showHideConfirmPassword input").attr("type", "text");
      $("#showHideConfirmPassword i").removeClass("fa-eye-slash");
      $("#showHideConfirmPassword i").addClass("fa-eye");
    }
  });
  $("#logout").click(function () {
    window.location.href = "login.html";
  });
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]',
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl),
  );
  var date = new Date();
  var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
  $("#selectDate").datepicker({
    format: "yyyy",
    todayHighlight: true,
    autoclose: true,
    startView: "years",
    minViewMode: "years",
  });
  $("#selectDate").datepicker("setDate", today);
});
