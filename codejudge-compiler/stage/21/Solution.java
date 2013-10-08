import java.util.Scanner;
import java.math.BigInteger;

public class Solution {
    public static void main(String[] args) {
        Scanner s = new Scanner(System.in);
        System.out.println(new BigInteger(s.nextLine()).add(new BigInteger(s.nextLine())));
    }
}