<?php require_once 'includes/header.php'; ?>

<!-- Include jQuery UI CSS for Datepicker -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-check"></i> Order Report
            </div>
            <!-- /panel-heading -->
            <div class="panel-body">

                <form class="form-horizontal" action="php_action/getOrderReport.php" method="post" id="getOrderReportForm">
                    <div class="form-group">
                        <label for="startDate" class="col-sm-2 control-label">Start Date</label>
                        <div class="col-sm-10">
                            <!-- Updated to use datepicker -->
                            <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endDate" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-10">
                            <!-- Updated to use datepicker -->
                            <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success" id="generateReportBtn"> <i class="glyphicon glyphicon-ok-sign"></i> Generate Report</button>
                        </div>
                    </div>
                </form>

            </div>
            <!-- /panel-body -->
        </div>
    </div>
    <!-- /col-dm-12 -->
</div>
<!-- /row -->

<!-- Generated report content container -->
<div id="generatedReportContainer"></div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Include jQuery UI JS for Datepicker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../seller/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="../seller/js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="custom/js/report.js"></script>
<script>
    $(document).ready(function () {
        // Initialize datepicker with dd-mm-yy format
        $("#startDate").datepicker({
            dateFormat: "dd-mm-yy"
        });
        $("#endDate").datepicker({
            dateFormat: "dd-mm-yy"
        });

        // Existing AJAX form submission code
        $("#getOrderReportForm").submit(function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Perform AJAX request to generate the report
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: $(this).serialize(),
                dataType: 'html',
                success: function (response) {
                    // Display the generated report content
                    $('#generatedReportContainer').html(response);

                    // Create "Generate Receipt" button
                    var generateReceiptButton = $('<button>', {
                        class: 'btn btn-primary',
                        html: '<i class="glyphicon glyphicon-list-alt"></i> Generate Receipt',
                        id: 'generateReceiptBtn'
                    });

                    // Append the button to the report container
                    $('#generatedReportContainer').append(generateReceiptButton);

                    // Initialize DataTables on the generated report content
                    $('#generatedReportTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'pdfHtml5'
                        ]
                    });

                    // Add click event listener to the "Generate Receipt" button
                    generateReceiptButton.on('click', function () {
                        console.log('Generate Receipt button clicked'); // Add this line for debugging

                        // Perform AJAX request to generate the receipt
                        $.ajax({
                            url: 'generateReceipt.php',
                            type: 'POST',
                            data: $('#getOrderReportForm').serialize(),
                            dataType: 'html',
                            success: function (receiptContent) {
                                // Display the receipt content on the page
                                $('#generatedReportContainer').append('<div class="receipt-content">' + receiptContent + '</div>');
                            }
                        });
                    });
                }
            });
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
