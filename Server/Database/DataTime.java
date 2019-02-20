import java.util.Calendar;

public class DataTime
{
	public static void main(String[] args)
	{
		Calendar calendar = Calendar.getInstance();
		int year = calendar.get(Calendar.YEAR);
		int hour = calendar.get(Calendar.MONTH);
		System.out.println(year+","+hour);
	}
}
