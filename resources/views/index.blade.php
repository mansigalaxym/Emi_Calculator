<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <link rel="stylesheet" href="{{asset('front_assests/css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calculator</title>
</head>

<body>

    <div class="loan-calculator">
        <div class="top">
            <h2>Loan Calculator</h2>

            <form action="#">
                <div class="group">
                    <div class="title">Amount</div>
                    <span id="amnt">1000</span>
                    <input type="range" name="amount" id="amount" min="1000" max="10000" value="1000" class="slider" id="myRange">
                    <!-- <input type="range" min="100" max="2000000" value="1000000" step="100000" class="slider" id="amount"> -->
                </div>
                <div class="group">
                    <div class="title">Interest Rate</div>
                    <span id="IR">10%</span>
                    <input type="range" name="rate" id="rate" min="1" max="20" value="1" step="0.1" class="slider" id="interest">
                </div>
                <div class="group">
                    <div class="title">Tenure (in months)</div>
                    <span id="years">1</span><span id="months"> ( 12 Months )</span>
                    <input type="range" name="time" id="time" min="1" max="30" value="1" class="slider" id="year">
                </div>
                <div class="group">
                    <div class="title">Select EMI Start Date</div>

                    <input type="date" id="emiDate" name="emiDate" value="2022-02-22">
                </div>
            </form>
            @csrf
        </div>
        <div class="result">
            <div class="left">
                <div class="loan-emi">
                    <h3>Loan EMI</h3>
                    <div class="value" id="emi">123</div>
                </div>
                <div class="total-interest">
                    <h3>Total Interest Payable</h3>
                    <div class="value" id="intrstpay">7.5</div>
                </div>
                <div class="total-amount">
                    <h3>Total Amount</h3>
                    <div class="value" id="Totalpay">12345</div>
                </div>
                <!-- <button class="calculate-btn">Calculate</button> -->
            </div>
            <div class="right">
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
    <div class="table">
        <table id="mainTable">
            <tr>
                <th>SNo.</th>
                <th>Payment Date</th>
                <th>Monthly EMI</th>
                <th>Interest Paid</th>
                <th>Principle Paid</th>
                <th>Balance</th>
            </tr>

        </table>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // This is for 1*******************************************************************/
        $(document).ready(function() {
            $('#rate').on('change', function() {
                let rate = $(this).val();
                var Amount = document.getElementById('amount').value;
                var Years = document.getElementById('time').value;
                var emiDate = document.getElementById('emiDate').value;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "post",
                    url: "/getRate",
                    data: {
                        'rate': rate,
                        'amount': Amount,
                        'time': Years,
                        'emiDate': emiDate,
                    },
                    success: function(response) {
                        //console.log(response.rate);
                        $('#IR').html(response.rate + '%');
                        $('#emi').html(' ' + parseFloat(response.EMI).toFixed(2));
                        $('#intrstpay').html(' ' + parseFloat(response.TIPA).toFixed(2));
                        $('#Totalpay').html(' ' + parseFloat(response.TPA).toFixed(2));
                        $('#mainTable').find("tr:not(:first)").remove();




                        var p = Amount;
                        var r = rate / 12 / 100;
                        var n = Years * 12;
                        var balance = p;
                        var period = n;
                        var emi = parseFloat(response.EMI).toFixed(2);
                        var dt = new Date(emiDate);

                        for (i = 1; i <=period; i++) {
                            dt.setMonth(dt.getMonth() + 1);
                            var day = dt.getDate();
                            var month = dt.getMonth() + 1;
                            var year = dt.getFullYear();


                           // console.log(day + '/' + month + '/' + year);
                            var interest = parseFloat(((rate / 100) * balance) / 12).toFixed(2);
                            var principal = parseFloat(emi - interest).toFixed(2);
                            balance = parseFloat(balance - principal).toFixed(2);
                            $('#mainTable').append('<tr><td>' + i + '</td><td>' + day + '/' + month + '/' + year + '</td><td>' + 'Rs. ' + emi + '</td><td>' + 'Rs. ' + interest + '</td><td>' + 'Rs. ' + principal + '</td><td>' + 'Rs. ' + balance + '</td></tr>');
                        }
                        /*********************************************************/
                       // console.log(response.TIPA);
                        var xValues = ["Interst Amount", "Total Amount"];
                        var yValues = [response.TIPA, response.TPA];
                        var barColors = [
                            "#b91d47",
                            "#00aba9",

                        ];

                        new Chart("myChart", {
                            type: "doughnut",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                title: {
                                    // display: true,
                                    // text: "World Wide Wine Production 2018"
                                }
                            }
                        });




                    }
                });
            });
        });

        // This is for 2*******************************************************************/
        $(document).ready(function() {
            $('#amount').on('change', function() {
                let amount = $(this).val();
                var rate = document.getElementById('rate').value;
                var Years = document.getElementById('time').value;
                var emiDate = document.getElementById('emiDate').value;
                // console.log(rate);
                // console.log(Years);
                // console.log(amount);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "post",
                    url: "/getAmount",
                    data: {
                        'amount': amount,
                        'rate': rate,
                        'time': Years,
                        'emiDate': emiDate,
                    },
                    success: function(response) {
                        //console.log(response.rate);
                        $('#amnt').html(' ' + response.amount);
                        $('#emi').html(' ' + parseFloat(response.EMI).toFixed(2));
                        $('#intrstpay').html(' ' + parseFloat(response.TIPA).toFixed(2));
                        $('#Totalpay').html(' ' + parseFloat(response.TPA).toFixed(2));
                        $('#mainTable').find("tr:not(:first)").remove();
                        var p = amount;
                        var r = rate / 12 / 100;
                        var n = Years * 12;
                        var balance = p;
                        var period = n;
                        var emi = parseFloat(response.EMI).toFixed(2);
                        var dt = new Date(emiDate);
                        for (i = 1; i <= period; i++) {
                            dt.setMonth(dt.getMonth() + 1);
                            var day = dt.getDate();
                            var month = dt.getMonth() + 1;
                            var year = dt.getFullYear();

                            var interest = parseFloat(((rate / 100) * balance) / 12).toFixed(2);
                            var principal = parseFloat(emi - interest).toFixed(2);
                            balance = parseFloat(balance - principal).toFixed(2);
                            $('#mainTable').append('<tr><td>' + i + '</td><td>' + day + '/' + month + '/' + year + '</td><td>' + 'Rs. ' + emi + '</td><td>' + 'Rs. ' + interest + '</td><td>' + 'Rs. ' + principal + '</td><td>' + 'Rs. ' + balance + '</td></tr>');
                        }



                        /**************************************************/
                        var xValues = ["Interst Amount", "Total Amount"];
                        var yValues = [response.TIPA, response.TPA];
                        var barColors = [
                            "#b91d47",
                            "#00aba9",

                        ];

                        new Chart("myChart", {
                            type: "doughnut",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                title: {
                                    // display: true,
                                    // text: "World Wide Wine Production 2018"
                                }
                            }
                        });


                    }
                });
            });
        });


        // This is for 3*******************************************************************/
        $(document).ready(function() {
            $('#time').on('change', function() {
                let Years = $(this).val();
                var Amount = document.getElementById('amount').value;
                var rate = document.getElementById('rate').value;
                var emiDate = document.getElementById('emiDate').value;
                // console.log(rate);
                // console.log(time);
                // console.log(Amount);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: "post",
                    url: "/getTimet",
                    data: {
                        'time': Years,
                        'amount': Amount,
                        'rate': rate,
                        'emiDate': emiDate,
                    },
                    success: function(response) {
                        //console.log(response.rate);
                        $('#years').html(response.time);
                        $('#months').html(' ( ' + response.months + ' Months ) ');
                        $('#emi').html(' ' + parseFloat(response.EMI).toFixed(2));
                        $('#intrstpay').html(' ' + parseFloat(response.TIPA).toFixed(2));
                        $('#Totalpay').html(' ' + parseFloat(response.TPA).toFixed(2));
                        $('#mainTable').find("tr:not(:first)").remove();
                        var p = Amount;
                        var r = rate / 12 / 100;
                        var n = Years * 12;
                        var balance = p;
                        var period = n;
                        var emi = parseFloat(response.EMI).toFixed(2);
                        var dt = new Date(emiDate);
                        for (i = 1; i <= period; i++) {
                            dt.setMonth(dt.getMonth() + 1);
                            var day = dt.getDate();
                            var month = dt.getMonth() + 1;
                            var year = dt.getFullYear();

                            var interest = parseFloat(((rate / 100) * balance) / 12).toFixed(2);
                            var principal = parseFloat(emi - interest).toFixed(2);
                            balance = parseFloat(balance - principal).toFixed(2);
                            $('#mainTable').append('<tr><td>' + i + '</td><td>' + day + '/' + month + '/' + year + '</td><td>' + 'Rs. ' + emi + '</td><td>' + 'Rs. ' + interest + '</td><td>' + 'Rs. ' + principal + '</td><td>' + 'Rs. ' + balance + '</td></tr>');
                        }
                        /******************************************************/
                        var xValues = ["Interst Amount", "Total Amount"];
                        var yValues = [response.TIPA, response.TPA];
                        var barColors = [
                            "#b91d47",
                            "#00aba9",

                        ];

                        new Chart("myChart", {
                            type: "doughnut",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                title: {
                                    // display: true,
                                    // text: "World Wide Wine Production 2018"
                                }
                            }
                        });

                    }
                });
            });
        });
    </script>
    <!-- <script>
        var xValues = ["Interst Amount", "Total Amount"];
        var yValues = [27.9, 527.9];
        var barColors = [
            "#b91d47",
            "#00aba9",

        ];

        new Chart("myChart", {
            type: "doughnut",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    // display: true,
                    // text: "World Wide Wine Production 2018"
                }
            }
        });
    </script> -->
</body>

</html>