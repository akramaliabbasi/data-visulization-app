<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Visualization App</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-container {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two equal columns */
            gap: 10px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            grid-column: span 2; /* Make the button span both columns */
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        #chart_div {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <label for="host">Database Host:</label>
        <input type="text" id="host" name="host" value="localhost">
        <label for="database">Database Name:</label>
        <input type="text" id="database" name="database" value="data_visualization">
        <label for="user">Database User:</label>
        <input type="text" id="user" name="user" value="root">
        <label for="password">Database Password:</label>
        <input type="password" id="password" name="password" value="">
    </div>

    <div class="form-container">
        <label for="driver">Database Driver:</label>
        <select id="driver" name="driver">
            <option value="mysql" selected>MySQL</option>
            <option value="pgsql">PostgreSQL</option>
            <!-- Add other supported drivers as needed -->
        </select>
        <div>
            <label for="table">Select Table:</label>
            <select id="table" name="table"></select>
        </div>
        <button onclick="connectDatabase()">Connect</button>
    </div>
</div>

<div class="form-container">
    <label for="query">Enter Query:</label>
    <textarea id="query" name="query" rows="4" cols="50" value="SELECT
    MONTH(saleDate) as saleMonth,
    SUM(quantity) as totalQuantitySold
FROM sales
GROUP BY MONTH(saleDate)
ORDER BY MONTH(saleDate);">
	SELECT
    MONTH(saleDate) as saleMonth,
    SUM(quantity) as totalQuantitySold
FROM sales
GROUP BY MONTH(saleDate)
ORDER BY MONTH(saleDate);

</textarea>
    <button onclick="executeQuery()">Run Query</button>
</div>

<div id="chart_div"></div>

<script src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { 'packages': ['corechart'] });
	google.charts.setOnLoadCallback(initialize);

	function initialize() {
		// Initial chart setup or any other initialization steps

		// Example: Draw an initial chart with empty data
		drawChart([]);
	}
		
	function drawChart(data) {
		var options = {
			title: 'Total Quantity Sold per Month',
			hAxis: { title: 'Month', titleTextStyle: { color: '#333' } },
			vAxis: { minValue: 0 },
			chartArea: { width: '80%', height: '70%' },
			colors: ['blue', 'red'], // Set colors for the lines
			isStacked: true, // Stack the lines to start from the bottom
		};

		// Sort the data by saleMonth
		data.sort(function(a, b) {
			return a.saleMonth - b.saleMonth;
		});

		var chartData = new google.visualization.DataTable();
		chartData.addColumn('string', 'Month');
		chartData.addColumn('number', 'Quantity Sold Line 1'); // Line 1
		chartData.addColumn('number', 'Quantity Sold Line 2'); // Line 2

		// Assuming the response data is an array of objects with 'saleMonth' and 'totalQuantitySold' properties
		data.forEach(function(row) {
			// Parse totalQuantitySold as a number
			var quantitySold = parseInt(row.totalQuantitySold, 10);

			chartData.addRow([row.saleMonth.toString(), quantitySold, quantitySold * 1.5]); // Example multiplier for Line 2
		});

		var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
		chart.draw(chartData, options);
	}


	   
	function executeQuery() {
		var query = document.getElementById('query').value;

		// Include CSRF token in headers
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// Send an AJAX request to execute the query
		$.ajax({
			type: 'POST',
			url: '/execute-query',
			data: { query: query },
			success: function(response) {
				console.log(response);

				// Check if the response contains data
				if (response.hasOwnProperty('data')) {
					// Call drawChart with the received data
					drawChart(response.data);
				} else {
					console.error('No data received from the server.');
				}
			},
			error: function(error) {
				console.error(error);
			}
		});
	}


	
	function connectDatabase() {
    var driver = document.getElementById('driver').value;
    var host = document.getElementById('host').value;
    var database = document.getElementById('database').value;
    var user = document.getElementById('user').value;
    var password = document.getElementById('password').value;

    // Include CSRF token in headers
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Send an AJAX request to the server to set the configuration
    $.ajax({
        type: 'POST',
        url: '/set-database-config',
        data: {
            driver: driver,
            host: host,
            database: database,
            user: user,
            password: password
        },
        success: function(response) {
            console.log(response);

            // If the configuration is updated successfully, fetch table names
            if (response.message === 'Configuration updated successfully') {
					fetchTableNames();
			}
			},
			error: function(error) {
				console.error(error);
			}
		});
	}

	function fetchTableNames() {
		// Include CSRF token in headers
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			type: 'GET',
			url: '/get-table-names',
			success: function(response) {
				var tableDropdown = document.getElementById('table');

				// Clear existing options
				tableDropdown.innerHTML = '';

				// Populate the dropdown with retrieved table names
				response.tables.forEach(function(tableName) {
					var option = document.createElement('option');
					option.value = tableName;
					option.text = tableName;
					tableDropdown.add(option);
				});
			},
			error: function(error) {
				console.error(error);
			}
		});
	}




</script>

</body>
</html>
