import java.sql.*;

public class DB
{
	private Connection DBconnection;
	private Statement statement;
	private static String url = "jdbc:mysql://localhost:3306/";
	private static String account = "root";
	private static String pwd = "fucknopwd0";
	
	
	public static void main (String[] args) throws SQLException
	{
		String a="as", b="asd";
		DB db = new DB("gps");
		db.insertGpsData(0,1,2,3,4,5,6,a,b);
		db.close();
	}
	
	
	public DB(String Database)
	{
		try
		{
			init(Database);
		}
		catch (ClassNotFoundException | SQLException e)
		{
			e.printStackTrace();
		}
	}
	
	
	private void init(String Database) throws ClassNotFoundException, SQLException
	{
		Class.forName("com.mysql.jdbc.Driver");
		DBconnection = DriverManager.getConnection(url+Database,account,pwd);
		statement = DBconnection.createStatement();
	}
	
	
	public ResultSet select(String table, String key) throws SQLException
	{
		return statement.executeQuery("select "+key+" from "+table);
	}
	

	public void insertGpsData(int id, int year, int month, int day, int hour, int minute, int second, String longitude, String latitude) throws SQLException
	{
		statement.executeUpdate("insert into GpsData values ("+id+","+year+","+month+","+day+","+hour+","+minute+","+second+",'"+longitude+"','"+latitude+"')");
	}

	public void close()
	{
		try
		{
			statement.close();
			DBconnection.close();
		}
		catch(SQLException e)
		{
			e.printStackTrace();
		}
	}
}
