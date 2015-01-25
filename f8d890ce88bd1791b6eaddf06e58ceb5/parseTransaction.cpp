#include <iostream>
#include <fstream>
#include <sstream>
#include <istream>
#include <string.h>
#include <stdlib.h>
#include <vector>

/////////////////////////////////////////
// Transaction data should contain     //
//-------------------------------------//
// Transaction receiver: varchar(10)   //
// Transaction token/TAN: varchar(15)  //
// Transaction amount: double          //
/////////////////////////////////////////

class Transaction{
    // A class to hold the transaction data read from a file
    // Private by default
    std::string transactionReceiver;
    double transactionAmount;
    std::string transactionToken;
    std::string transactionDesc;
    
    public:
        Transaction (std::string, double, std::string, std::string);
        void setReceiver(std::string tReceiver);
        void setAmount(double tAmount);
        void setToken(std::string tToken);
        void setDesc(std::string tDesc);
        std::string getReceiver();
        double getAmount();
        std::string getToken();
        std::string getDesc();
};

Transaction::Transaction(std::string tReceiver, double tAmount, std::string tToken, std::string tDesc){
    this->transactionReceiver = tReceiver;
    this->transactionAmount = tAmount;
    this->transactionToken = tToken;
    this->transactionDesc = tDesc;
}
void Transaction::setReceiver(std::string tReceiver){ this->transactionReceiver = tReceiver; }
void Transaction::setAmount(double tAmount){ this->transactionAmount = tAmount; }
void Transaction::setToken(std::string tToken){ this->transactionToken = tToken; }
void Transaction::setDesc(std::string tDesc){ this->transactionDesc = tDesc; }

std::string Transaction::getReceiver(){ return this->transactionReceiver; }
double Transaction::getAmount(){ return this->transactionAmount; }
std::string Transaction::getToken(){ return this->transactionToken; }
std::string Transaction::getDesc(){ return this->transactionDesc; }

// Creates a JSON string from an array of Transactions to be POSTed to a PHP page
std::string createJSON(std::vector<Transaction> aTransactions){
    std::string amountString = "";
    std::string transactions = "{\"transactions\": [";
    for ( std::vector<Transaction>::iterator itr = aTransactions.begin(); itr < aTransactions.end(); ++itr ){
        Transaction t = *itr;
        transactions += "{\"receiver\": \"";
        transactions += t.getReceiver();
        transactions += "\", \"token\": \"";
        transactions += t.getToken();
        transactions += "\", \"amount\": \"";
        std::stringstream convert;
        convert << t.getAmount();
        convert >> amountString;
        transactions += amountString;
        transactions += "\", \"desc\": \"";
        transactions += t.getDesc();
        transactions += "\"}, ";
    }
    transactions = transactions.substr(0, transactions.length() - 2); // Clip the last comma
    transactions += "]}";
    return transactions;
}

// Parses the contents of a file, retrieves Transactions and appends them to an array
void parseTransactionFile(std::vector<Transaction>& aTransactions, std::string fileName){
    try{
        std::string dummyField = "";
        int fieldCount = 1;
        Transaction dummyT = Transaction("", 0, "", "");
        std::ifstream tFile(fileName.c_str());
        std::string fileContent((std::istreambuf_iterator<char>(tFile)) ,(std::istreambuf_iterator<char>()));
        if(fileContent.length() < 1 )
            return;
       tFile.close(); // Close file and start working on the local string
       
       // Parse file to retrieve transactions
       std::istringstream ss(fileContent);
       std::string transactionString; // Holds the body of the transaction
       std::vector<std::string> allTransactions;
       while(std::getline(ss, transactionString,'#')){
           allTransactions.push_back(transactionString);
       }
       
       // Now iterate on the tokens
       for(std::vector<std::string>::iterator it = allTransactions.begin(); it != allTransactions.end(); it++){
           transactionString = *it; // Get transaction body
           std::istringstream ss(transactionString);
           int fieldCount = 1;
           std::string token;
           // Loop on transaction fields
           dummyT.setDesc("No description.");
           while(std::getline(ss, token, '\n')){
               if(token.length() > 0){
                   if(fieldCount % 4 == 1) { dummyT.setReceiver(token); fieldCount +=1;}
                   else if(fieldCount % 4 == 2) { dummyT.setToken(token); fieldCount +=1;}
                   else if(fieldCount % 4 == 3) { dummyT.setAmount(atoi(token.c_str())); fieldCount +=1;}
                   else if(fieldCount % 4 == 0){
                       dummyT.setDesc(token);
                   }
               }
           }
           aTransactions.push_back(dummyT);
       }
    }catch(std::exception& e){
       std::cout << "Error encountered: " << e.what() << std::endl;
   }    
}

int main(int argc, char* argv[]){
    std::string fileName;
    try{
        // Check if number of arguments imply a filename has not been passed
        if(argc < 2)
            return -1;
        else
            fileName = argv[1]; // Assume the first argument is the file name
       
        std::vector<Transaction> allTransactions; // Create a vector of Transactions
        parseTransactionFile(allTransactions, fileName); // .. and pass it [default by reference] for parsing
        // std::cout << "Size: " << allTransactions.size() << std::endl;
        
        // Create a JSON file of all retrieved Transactions and pass it to a PHP page to carry out the transaction
        std::string jsonTransactions = createJSON( allTransactions );
        std::cout << jsonTransactions;
        
        return 1;
   }catch(std::exception& e){
       std::cout << "Error encountered: " << e.what() << std::endl;
       return -1;
   }
}

