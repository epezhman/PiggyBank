package sc.group15;

import java.security.MessageDigest;
import java.util.Random;

public class OTPGenerator {

	private static int lastRand = -1;

	public static String Generate(String PIN, String amount, String account) {
		try {
			String toBeHashed = amount + account;
			String salt = PIN + new StringBuilder(PIN).reverse().toString();

			MessageDigest md = MessageDigest.getInstance("SHA-256");

			int interval = md.digest((toBeHashed + salt).getBytes("UTF-8"))[0];
			interval = interval > 0 ? interval : -1 * interval;
			interval = interval % 15;
			boolean flag = true;
			do {
				interval = new Random().nextInt(interval);
				if(interval != lastRand)
				{
					lastRand = interval;
					flag = false;
				}

			} while (flag);

			md.reset();
			byte byteData[] = md.digest((toBeHashed + salt).getBytes("UTF-8"));

			for (int i = 0; i < interval; i++) {
				md.reset();
				byteData = md.digest(byteData);
			}

			StringBuffer sb = new StringBuffer();
			for (int i = 0; i < byteData.length; i++) {
				sb.append(Integer.toString((byteData[i] & 0xff) + 0x100, 16)
						.substring(1));
			}

			return sb.toString();

		} catch (Exception e) {
			return "";
		}
	}
}
