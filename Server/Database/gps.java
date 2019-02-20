import java.util.Calendar;
import java.io.IOException;
import java.sql.*;

public class gps
{
	public static int year, month, day, hour, minute, second, id;
	public static String longitude, latitude;
	public static DB db;
	public static SocketServer socketServer;
	public static String input;

	public static void main(String[] args) throws IOException,SQLException
	{
		int flag = 0;
		socketServer = new SocketServer();
		socketServer.Server();
		db = new DB("gps");	
		id = 0;
		while(true)
		{
			input = socketServer.getInput();
			System.out.println("111"+input);
			while(!input.equals("OK"))
			{
				if(input.equals("END")) { flag = 1; break;}
				input = socketServer.getInput();
				System.out.println(input);
			}
			System.out.println(input);
			if(flag == 1) break;
			longitude = socketServer.getLongitude();
			latitude = socketServer.getLatitude();
			getDataTime();
			id = id + 1;
			insertToMysql();
		}
		db.close();
		socketServer.close();
	}

	public static void getDataTime()
	{
		Calendar calendar = Calendar.getInstance();
		year = calendar.get(Calendar.YEAR);
		month = calendar.get(Calendar.MONTH);
		day = calendar.get(Calendar.DAY_OF_MONTH);
		hour = calendar.get(Calendar.HOUR_OF_DAY);
		minute = calendar.get(Calendar.MINUTE);
		second = calendar.get(Calendar.SECOND);
	}

	public static void insertToMysql() throws SQLException
	{
		db.insertGpsData(id, year, month, day, hour, minute, second, longitude, latitude);	
	}
}
