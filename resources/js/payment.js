$(".amount").hide();

$("#client_payment_type").on("change", function () {
    let value = $(this).val();
    let full_payment = $("#payable_amount").val();

    if (value == "2") {
        $(".amount").hide();
        $("#amount").val(full_payment).prop("readonly", true);
    } else if (value == "3") {
        $(".amount").show();
        $("#amount").val("").prop("readonly", false);
    } else {
        $(".amount").hide();
        $("#amount").prop("readonly", false);
    }
});

$(document).on("keyup", "#amount", function () {
    let payable_amount = parseFloat($("#payable_amount").val());
    let amount = parseFloat($("#amount").val());
    let paymentType = $("#client_payment_type").val();
  
    if (paymentType === '3' && payable_amount < amount) {
    
        $("#error-msg").text(
            Lang.get(
                "js.amount_should_be_less_than_payable_amount"
            )
        );
        $("#btnPay").addClass("disabled");
    } else if (paymentType === '2' && payable_amount < amount) {
        $("#error-msg").text(
            Lang.get(
                "js.amount_should_be_less_than_payable_amount"
            )
        );
        $("#btnPay").addClass("disabled");
    } else {
        $("#error-msg").text("");
        $("#btnPay").removeClass("disabled");
    }
});

$("#clientPaymentForm").on("submit", function (e) {
    e.preventDefault();
    // if ($('#error-msg').text() !== '') {
    //     return false
    // }
    let paymentMode = $("#client_payment_mode").val();
    if ($("#amount").val() == 0) {
        alert("Amount should not be equal to zero");
        return false;
    }

    if ($("#payment_note").val().trim().length == 0) {
        alert("Note field is Required");
        return false;
    }

    let btnSubmitEle = $(this).find("#btnPay");
    setAdminBtnLoader(btnSubmitEle);
    let payloadData = {
        amount: parseFloat($("#amount").val()),
        invoiceId: parseInt($("#client_invoice_id").val()),
        transactionNotes: $("#payment_note").val(),
    };

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    let invoiceStripePaymentUrl = route("client.stripe-payment");

    if (paymentMode == 1) {
        $.ajax({
            url: route("clients.payments.store"),
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (result) {
                if (result.success) {
                    window.location.href = result.data.redirectUrl;
                }
            },
            error: function (result) {
                alert(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 2) {
        $.post(invoiceStripePaymentUrl, payloadData)
            .done((result) => {
                let redirectUrl = result.data.redirectUrl;
                window.location.href = redirectUrl;
            })
            .catch((error) => {
                alert(error.responseJSON.message);
                setAdminBtnLoader(btnSubmitEle);
            });
    } else if (paymentMode == 3) {
        $.ajax({
            type: "GET",
            url: route("paypal.init"),
            data: {
                amount: payloadData.amount,
                invoiceId: payloadData.invoiceId,
                transactionNotes: payloadData.transactionNotes,
            },
            success: function (result) {
                if (result.status == "CREATED") {
                    let redirectTo = "";

                    $.each(result.links, function (key, val) {
                        if (val.rel == "approve") {
                            redirectTo = val.href;
                        }
                    });
                    location.href = redirectTo;
                } else {
                    location.href = result.url;
                }
            },
            error: function (result) {
                alert(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 5) {
        $.ajax({
            type: "GET",
            url: route("razorpay.init"),
            data: $(this).serialize(),
            success: function (result) {
                if (result.success) {
                    let options = {
                        key: result.data.key,
                        amount: result.data.amount,
                        currency: result.data.currency,
                        order_id: result.data.id,
                        description: result.data.description,
                        notes: {
                            invoiceId: result.data.invoiceId,
                        },
                        image: "{{ getLogoUrl() }}",
                        callback_url: route("razorpay.success"),
                        prefill: {
                            email: result.data.email,
                            name: result.data.name,
                            invoiceId: result.data.invoiceId,
                        },
                        readonly: {
                            name: true,
                            email: true,
                        },
                        modal: {
                            ondismiss: function () {
                                alert("Payment Failed");
                                window.location.reload();
                            },
                        },
                    };

                    let razorPay = new Razorpay(options);
                    razorPay.open();
                    razorPay.on("payment.failed", function () {
                        alert("Payment Failed");
                        window.location.reload();
                    });
                }
            },
            error: function (result) {
                alert(result.responseJSON.message);
            },
            complete: function () {
                setAdminBtnLoader(btnSubmitEle);
            },
        });
    } else if (paymentMode == 6) {
        window.location.replace(
            route("client.paystack.init", {
                invoiceId: payloadData.invoiceId,
                amount: payloadData.amount,
                note: payloadData.transactionNotes,
            })
        );
    }
});

window.setAdminBtnLoader = function (btnLoader) {
    if (btnLoader.attr("data-loading-text")) {
        btnLoader
            .html(btnLoader.attr("data-loading-text"))
            .prop("disabled", true);
        btnLoader.removeAttr("data-loading-text");
        return;
    }
    btnLoader.attr("data-old-text", btnLoader.text());
    btnLoader.html(btnLoader.attr("data-new-text")).prop("disabled", false);
};

setTimeout(function () {
    $(".alert").fadeOut("slow");
}, 10000);