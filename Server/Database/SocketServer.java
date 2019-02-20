import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;

public class SocketServer
{
	public BufferedReader in, out;
	public PrintWriter writer;
	public ServerSocket server;
	public Socket socket;
	public static void main(String[] args) throws IOException
	{
		SocketServer socketServer = new SocketServer();
		socketServer.Server();
		String input, output;
		input = socketServer.getInput();
		System.out.println("Client: " + input);
		while(!input.equals("end"))
		{
			output = socketServer.out.readLine();
			socketServer.writer.println(output);
			socketServer.writer.flush();
			System.out.println("Server: " + output);
			input = socketServer.getInput();
			System.out.println("Client: " + input);
		}
		
		socketServer.close();	
	}
	public void Server()
	{
		try
		{
			server = null;
			try
			{
				server = new ServerSocket(5109);
				System.out.println("Start successfully!");
			}
			catch(Exception e)
			{
				System.out.println("Start fail:"+e);	
			}
			socket = null;
			try
			{
				socket = server.accept();
			}
			catch(Exception e)
			{
				System.out.println("Error:"+e);
			}
			in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
			out = new BufferedReader(new InputStreamReader(System.in));
			writer = new PrintWriter(socket.getOutputStream());
		}
		catch(Exception e)
		{
			System.out.println("Error:"+e);
		}
	}

	public String getLongitude() throws IOException
	{
		String longitude = in.readLine();
		System.out.println("Longitude:"+longitude);
		return longitude;
	}

	public String getLatitude() throws IOException
	{
		String latitude = in.readLine();
		System.out.println("Latitude:"+latitude);
		return latitude;
	}

	public String getInput() throws IOException
	{
		String input = in.readLine();
		return input;
	}

	public void close() throws IOException
	{
		writer.close();
		in.close();
		socket.close();
		server.close();
	}
}
