<!DOCTYPE html>

<html>
    <head>
        <title>Device Integration | Order List</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-theme.min.css" />
        <link rel='stylesheet' href='assets/customs/css/cd-design.css' />
    </head>
    <body>

        <div class='container-fluid'>
            <div class="page-header text-center">
                <h1>Order List for Devices <small>(All New Order Request)</small></h1>
            </div>
            
            <ul class='breadcrumb'>
                <li ><a href='./'>Home</a></li>
                <li class='active'>Order List</li>
            </ul>
            
            <div class='panel panel-info'>
                <div class='panel-heading text-uppercase'>Order Request for Medical Devices</div>
                <div class='panel-body'>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#all" data-toggle="pill">All</a></li>
                        <li><a href="#ultrasound" data-toggle="pill">Ultrasound</a></li>
                        <li><a href="#spirometer" data-toggle="pill">SpiroMeter</a></li>
                        <li><a href="#endoscopy" data-toggle="pill">Endoscopy</a></li>
                        <li><a href="#oral" data-toggle="pill">Oral</a></li>
                        <li><a href="#fundus" data-toggle="pill">Fundus</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="all" class="tab-pane fade in active">
                            <h3>All <small>All order list</small></h3>
                            <table id='all_orders' class="table table-bordered table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order Type</th>
                                        <th>Patient MRN</th>
                                        <th>Patient Name</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Ordered By</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div id="ultrasound" class="tab-pane fade">
                            <h3>Ultrasound <small>ultrasound list</small></h3>
                            <div id='table_ultrasound'></div>
                        </div>
                        <div id="spirometer" class="tab-pane fade">
                            <h3>Spirometer <small>spirometer list</small></h3>
                            <div id='table_spirometer'></div>
                        </div>
                        <div id="endoscopy" class="tab-pane fade">
                            <h3>Endoscopy <small>Endoscopy list</small></h3>
                            <div id='table_endoscopy'></div>
                        </div>
                        <div id="oral" class="tab-pane fade">
                            <h3>Oral <small>Oral list</small></h3>
                            <div id='table_oral'></div>
                        </div>
                        <div id="fundus" class="tab-pane fade">
                            <h3>Fundus <small>Fundus list</small></h3>
                            <div id='table_fundus'></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <footer class="text-center small">
                HIS@KKM | Integration R&D 2017
            </footer>
        </div>

        <?php
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "8081",
            CURLOPT_URL => "http://172.19.64.7:8081/integration/orders/orderlist",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Basic YWRtaW46YWRtaW4="
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        }
        ?>
        <script src="./assets/jquery/jquery-3.1.1.min.js"></script>
        <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
        <script language="javascript" type="text/javascript" src="assets/libs/jqplot/jquery.jqplot.min.js"></script>
        <script>
            function renderTable(table, data) {
                var html = '';
                var info = data.order_list;
                var filter = table.replace('table_', '');
                html += "<table class='table table-bordered table-condensed table-striped'>";
                html += "<thead>";
                html += "<tr>";
                html += "<th>No</th>";
                html += "<th>HIS Order Id</th>";
                html += "<th>Patient MRN</th>";
                html += "<th>Patient Name</th>";
                html += "<th>Appointment Date</th>";
                html += "<th>Status</th>";
                html += "<th>Ordered By</th>";
                html += "</tr>";
                html += "</thead>";
                html += "<tbody>";

                $(info).each(function (k, v) {
                    if (v.descipline == filter) {
                        html += "<tr>";
                        html += "<td>" + (k + 1) + "</td>";
                        html += "<td>" + v.his_order_id + "</td>";
                        html += "<td>" + v.patient_mrn + "</td>";
                        html += "<td>" + v.patient_name + "</td>";
                        html += "<td></td>";
                        html += "<td>" + v.status + "</td>";
                        html += "<td>" + v.ordered_by + "</td>";
                        html += "</tr>"
                    }
                });
                html += '</tbody>';
                html += '</table>';
                $('#' + table).html(html);
            }
        </script>
        <script>
            $(function () {
                var order_list = JSON.parse('<?= $response; ?>');
                var order_list_html = '';
                $(order_list.order_list).each(function (k, v) {
                    order_list_html += "<tr>";
                    order_list_html += "<td>" + (k + 1) + "</td>";
                    order_list_html += "<td>" + v.descipline + "</td>";
                    order_list_html += "<td>" + v.patient_mrn + "</td>";
                    order_list_html += "<td>" + v.patient_name + "</td>";
                    order_list_html += "<td></td>";
                    order_list_html += "<td>" + v.status + "</td>";
                    order_list_html += "<td>" + v.ordered_by + "</td>";
                    order_list_html += "</tr>";
                })
                $('#all_orders tbody').html(order_list_html);

                var tables = $("div[id^=table_]");

                $(tables).each(function (k, v) {
                    renderTable($(v).attr('id'), order_list);
                });

            });
        </script>
    </body>
</html>