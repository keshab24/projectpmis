//when does computer win? when user wins? and when there is tie?
//
import java.util.Scanner;
import java.util.Random;

public class RPSLS {

	public static void main(String[] args) {
		// TODO Auto-generated method stub


		//Declarations
		Scanner scan = new Scanner(System.in);
		Random randGen = new Random();


	    //Variables

	   String playersChoice;
	   String computersChoice = "";

	   int computerInt;

	   System.out.println("How about the best of competetion?\n" +
               "Please enter Rock, Paper, Scissor, Lizard or Spock:");
	   playersChoice = scan.next();

	   //generate computer's choice (1, 2, 3, 4, 5)
	   computerInt = randGen.nextInt(5)+1;

	    if (computerInt == 1)
		       computersChoice = "Rock";
	    else if (computerInt == 2)
		       computersChoice = "Paper";
	    else if (computerInt == 3)
		       computersChoice = "Scissor";
	    else if (computerInt == 4)
		    	computersChoice = "Lizard";
		else if (computerInt == 5)
		    	computersChoice = "Spock";


	    // Print Computers Choice
	    System.out.println("Computer choice is: " + computersChoice);
	    //I'll work on my end with the code and send you the cdes...please select all from your MACA:D




           if (playersChoice == computersChoice)
    	       System.out.println("It's a tie!");
           //my play
           else if (playersChoice == ("Scissor"))
        		if (computersChoice == ("Paper"))
              System.out.println("Scissor cuts paper. You win!!");

           else if (playersChoice == ("Paper"))
        	    if (computersChoice == ("Rock"))
        	   System.out.println("Paper covers rock. You win!!");

           else if (playersChoice == ("Rock"))
        		if (computersChoice == ("Lizard"))
        	   System.out.println("Rock crushes lizard. You win!!");


           else if (playersChoice == ("Lizard"))
        		if (computersChoice == ("Spock "))
        	   System.out.println("Lizard poisons spock. You win!!");


           else if (playersChoice == ("Spock"))
        	    if (computersChoice == ("Scissor"))
        	   System.out.println("Spock smashes scissors. You win!!");


           else if (playersChoice == ("Scissor"))
                if (computersChoice == ("Lizard"))
        	   System.out.println("Scissors decapitates lizard. You win!!");


           else if (playersChoice == ("Lizard"))
        		if (computersChoice == ("Paper"))
        	   System.out.println("Lizard eats paper. You win!!");


           else if (playersChoice == ("Paper"))
        		if (computersChoice == ("Spock"))
        	   System.out.println("Paper disproves Spock. You win!!");


           else if (playersChoice == ("Spock"))
        	    if (computersChoice == ("Rock"))
        	   System.out.println("Spock vaporizes rock. You win!!");

               //computer play
           else if (playersChoice == ("Rock"))
        	    if (computersChoice == ("Scissor"))
        	   System.out.println("Rock crushes scissors. computer win!!");

           else if (playersChoice == ("Paper"))
        	    if (computersChoice == ("Scissor"))
        	   System.out.println("Scissors cuts paper. computer win!!");


           else if (playersChoice == ("Rock"))
        	    if (computersChoice == ("Paper"))
        	   System.out.println("Paper covers rock. computer win!!");


           else if (playersChoice == ("Lizard"))
        	    if (computersChoice == ("Rock"))
        	   System.out.println("Rock crushes lizard. computer win!!");


           else if (playersChoice == ("Spock"))
        	    if (computersChoice == ("Lizard"))
        	   System.out.println("Lizard poisons Spock. computer win!!");

           else if (playersChoice == ("Scissor"))
        	    if (computersChoice == ("Spock"))
        	   System.out.println("Spock smashes scissors. computer win!!");
          //ok great


           else if (playersChoice == ("Lizard"))
        	    if (computersChoice == ("Scissor"))
        	   System.out.println("Scissors decapitates lizard. computer win!!");


           else if (playersChoice == ("Paper"))
        	    if (computersChoice == ("Lizard"))
        	   System.out.println(" Lizard eats paper. computer win!!");


           else if (playersChoice == ("Spock"))
        	    if (computersChoice == ("Paper"))
        	   System.out.println("Paper disproves Spock. computer win!!");


           else if (playersChoice == ("Rock"))
        	    if (computersChoice == ("Spock"))
        	   System.out.println("Spock vaporizes rock. computer win!!");


           else if (playersChoice == ("Scissor"))
        	    if (computersChoice == ("Rock"))
        	   System.out.println("Rock crushes scissors. computer win!!");

           else
  	         System.out.println("Invalid user input.");

	    		}
	    	}











