package SCS;

import java.security.MessageDigest;
//import java.util.Random;

public class OTPGenerator {

	//private static int lastRand = -1;
	private static int cntr = 0;
        private static String SCSKey = "__USERSCSTOKEN__";

	public static String Generate(String PIN, String amount, String account) {
		try {
			String toBeHashed = OTPGenerator.SCSKey + amount + account;
			String salt = PIN + System.currentTimeMillis()/1000L; //new StringBuilder(PIN).reverse().toString();
			MessageDigest md = MessageDigest.getInstance("SHA-1");

			md.reset();
			byte byteData[] = md.digest((toBeHashed + salt).getBytes("UTF-8"));

			StringBuffer sb = new StringBuffer();

			sb = new StringBuffer();
			for (int i = 0; i < byteData.length; i++) {
				sb.append(Integer.toString((byteData[i] & 0xff) + 0x100, 16)
						.substring(1));
			}

			return sb.toString();

		} catch (Exception e) {
			// return "";
			cntr++;
			if (cntr < 10)
				return Generate(PIN, amount, account);
			else return "";
		}
	}
}
