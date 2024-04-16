$(document).ready(function () {
    // Initialize the datepickers
    $("#startDate").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $("#endDate").datepicker({
        dateFormat: "dd-mm-yy"
    });

    // Bind the form submission event
    $("#getOrderReportForm").unbind('submit').bind('submit', function () {
        // Validate the start and end dates
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        if (startDate == "" || endDate == "") {
            if (startDate == "") {
                $("#startDate").closest('.form-group').addClass('has-error');
                $("#startDate").after('<p class="text-danger">The Start Date is required</p>');
            } else {
                $(".form-group").removeClass('has-error');
                $(".text-danger").remove();
            }

            if (endDate == "") {
                $("#endDate").closest('.form-group').addClass('has-error');
                $("#endDate").after('<p class="text-danger">The End Date is required</p>');
            } else {
                $(".form-group").removeClass('has-error');
                $(".text-danger").remove();
            }
        } else {
            $(".form-group").removeClass('has-error');
            $(".text-danger").remove();

            var form = $(this);

            // AJAX request to get the report
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'text',
                success: function (response) {
                    // Display the report in the container
                    $('#generatedReportContainer').html(response);

                    // Add the "Generate Receipt" button
                    var generateReceiptButton = $('<button>', {
                        id: 'generateReceiptBtn',
                        text: 'Generate Receipt',
                        class: 'btn btn-primary'
                    });
                    $('#generatedReportContainer').append(generateReceiptButton);

                    // Handle the "Generate Receipt" button click
                    $('#generateReceiptBtn').on('click', function () {
                        // AJAX request to generate the receipt
                        $.ajax({
                            url: 'php_action/generateReceipt.php',
                            type: 'POST',
                            data: form.serialize(),
                            dataType: 'text',
                            success: function (receiptResponse) {
                                // Handle the receipt response
                                // For example, display it in an alert or insert it into a div
                                alert(receiptResponse);
                            }
                        });
                    });
                }
            });
        }

        return false;
    });
});
