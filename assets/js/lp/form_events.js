function sendRequest(self) {
  const route = $("[route]").attr("route");
  const form = $(self).closest("form");
  console.log(form);
  const submit = form.find('button[type="submit"]');
  console.log(submit);
  submit.attr("disabled", true);
  const container = $("#loader");
  container.addClass("lmask");
  return $.ajax({
    url: route,
    method: "POST",
    data: form.serializeArray(),
    success: function (result) {
      $("#form-registration").html(result);
      container.removeClass("lmask");
    },
    error: function (err) {
      submit.attr("disabled", false);
      container.removeClass("lmask");
    },
  });
}

$("#registration-submit").on("click", function (e) {
  e.preventDefault();
  sendRequest(this);
});

$(".tile").on("click", function (e) {
  const $this = $(this);
  $this.siblings().removeClass("active");
  $this.addClass("active");
  const val = $this.attr("type");
  const option = $(`[name='places[type]']`).val(val);
  option.prop("selected", true);
  $(".place-type-error").hide();
});

$("#passwordWatch, #passwordWatch2").on("click", function (e) {
  const type =
    $(this).parent().find("input").attr("type") === "password"
      ? "text"
      : "password";
  const iconClass =
    $(this).parent().find("input").attr("type") === "password"
      ? "flaticon flaticon-eye-2 active-icon"
      : "flaticon flaticon-eye-2";
  $(this).attr("class", iconClass);
  $(this).parent().find("input").attr("type", type);
});

// if (typeof installationRes !== 'undefined' && installationRes !== '') {
//     const obj = JSON.parse(installationRes);
//     let newTab = window.open();
//     newTab.location.href = webUrl+'#/auth/login?email='+obj.email+'&auth-token='+obj.token;
// }

$("#submit-place").on("click", function (e) {
  e.preventDefault();
  const option = $(`[name='places[type]']`).val();
  if (option.length > 0) {
    $("#fill-modal").modal("show");
  } else {
    $(".place-type-error").show();
  }
});

window.submitWithoutProducts = function (e) {
  e.preventDefault();
  $("[name='places[withProducts]']").prop("checked", false);
  closeFillModal();
  sendRequest($("[name='places']").get(0));
};

window.submitWithProducts = function (e) {
  e.preventDefault();
  $("[name='places[withProducts]']").prop("checked", true);
  closeFillModal();
  sendRequest($("[name='places']").get(0));
};

function closeFillModal() {
  $("#fill-modal").modal("hide");
  $("body").removeClass("modal-open");
  $(".modal-backdrop").remove();
}

$("#submit-agreements").on("click", function (e) {
  e.preventDefault();
  var requiredAgreementsChecked = true;
  $("[name='agreementIds[]']").each(function (i, item) {
    if (!$(item).is(":checked") && $(item).attr("required")) {
      requiredAgreementsChecked = false;
      $(item)
        .parent()
        .parent()
        .find(".error")
        .addClass("d-block")
        .removeClass("d-none");
    }
  });

  if (requiredAgreementsChecked) {
    $(".user-agreements").hide();
    $(".user-data").removeClass("d-none");
  }
});

$("[name='agreementIds[]']").on("change input click", function () {
  if ($(this).is(":checked") && $(this).attr("required")) {
    $(this)
      .parent()
      .parent()
      .find(".error")
      .removeClass("d-block")
      .addClass("d-none");
  }
});
