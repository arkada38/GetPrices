<!DOCTYPE html>

<html>
	<head>
		<title>Get historical prices</title>
	</head>
	
	<body>
		<form action="getHistoricalPrices" method="get">
			<table>
				<tr>
					<td>
						Symbol:
					</td>
					<td>
						<input name="symbol" placeholder="symbol">
					</td>
					<td>
						Exchange:
					</td>
					<td>
						<input name="exchange" placeholder="exchange">
					</td>
				</tr>
				<tr>
					<td>
						Interval:
					</td>
					<td>
						<input name="interval" placeholder="interval">
					</td>
					<td>
						Period:
					</td>
					<td>
						<input name="period" placeholder="period">
					</td>
				</tr>
				<tr>
					<td colspan=4 align="right">
						<input type="submit" value="Get historical prices">
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>