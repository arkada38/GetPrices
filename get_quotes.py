from urllib.request import urlopen
import datetime


def get_quotes(symbol, exchange, interval=86400, period="10Y"):
    """
    Downloads and returns historical quotes in csv

    symbol
    The code of the security. For example, GOOG for Google or EURUSD for the Euro/Dollar currency pair.

    exchange
    The exchange where the security is listed. For example, NASDAQ for GOOG or CURRENCY for EURUSD.

    interval
    A number in seconds. Its minimum value is 60 seconds.

    period
    The period of time from which data will be returned. Always returns the most recent data.
    Examples of this parameter are 1d (one day), 1w (one week), 1m (one month), or 1y (one year).
    """
    symbol = symbol.upper()
    exchange = exchange.upper()

    url = "https://www.google.com/finance/getprices?q=" +\
          symbol + "&x=" + exchange + "&i=" + str(interval) + "&p=" + str(period) +\
          '&f=d,c,v,k,o,h,l'

    data = urlopen(url).read().decode('utf-8')
    rows = data.splitlines()

    # text_file = open("output.txt", "w")
    # text_file.write(data)
    # text_file.close()

    # with open('output.txt', 'r') as text_file:
    #     rows = [line.rstrip('\n') for line in text_file]

    if len(rows) < 7:
        print('Incorrect parameters')
        return

    timezone_offset = 0
    absolute_date = 0

    result = "UTC_Date,Market_Date,Open,High,Low,Close,Volume\n"

    for i in range(6, len(rows)):
        if rows[i][:len("TIMEZONE_OFFSET=")] == "TIMEZONE_OFFSET=":
            timezone_offset = int(rows[i][len("TIMEZONE_OFFSET="):])
            continue

        temp = rows[i].split(",")

        if temp[0][:1] == "a":
            absolute_date = int(temp[0][1:])
            utc_date = absolute_date
        else:
            utc_date = absolute_date + int(temp[0]) * interval

        market_date = datetime.datetime.fromtimestamp(utc_date + timezone_offset * 60).strftime('%Y-%m-%d %H:%M:%S')

        open_col = temp[4]
        high_col = temp[2]
        low_col = temp[3]
        close_col = temp[1]
        volume_col = temp[5]

        result += str(utc_date) + "," + market_date + "," +\
                  open_col + "," + high_col + "," + low_col + "," + close_col + "," + volume_col + "\n"

    return result


# print(get_quotes("AAPL", "NASD", interval=3600, period="100d"))
# get_quotes("AAPL", "NASD")
# get_quotes("GBPUSD", "CURRENCY", interval=900, period="3d")
# help(get_quotes)
