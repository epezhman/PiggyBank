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
    
    public:
        Transaction (std::string, double, std::string);
        void setReceiver(std::string tReceiver);
        void setAmount(double tAmount);
        void setToken(std::string tToken);
        std::string getReceiver();
        double getAmount();
        std::string getToken();
};

Transaction::Transaction(std::string tReceiver, double tAmount, std::string tToken){
    this->transactionReceiver = tReceiver;
    this->transactionAmount = tAmount;
    this->transactionToken = tToken;
}
void Transaction::setReceiver(std::string tReceiver){ this->transactionReceiver = tReceiver; }
void Transaction::setAmount(double tAmount){ this->transactionAmount = tAmount; }
void Transaction::setToken(std::string tToken){ this->transactionToken = tToken; }

std::string Transaction::getReceiver(){ return this->transactionReceiver; }
double Transaction::getAmount(){ return this->transactionAmount; }
std::string Transaction::getToken(){ return this->transactionToken; }

// Creates a JSON string from an array of Transactions to be POSTed to a PHP page
std::string createJSON(std::vector<Transaction> aTransactions){
    std::stringstream convert;
    std::string amountString = "";
    std::string transactions = "{\"transactions\": [";
    for ( std::vector<Transaction>::iterator itr = aTransactions.begin(); itr < aTransactions.end(); ++itr ){
        Transaction t = *itr;
        transactions += "{\"receiver\": \"";
        transactions += t.getReceiver();
        transactions += "\", \"token\": \"";
        transactions += t.getToken();
        transactions += "\", \"amount\": \"";
        convert << t.getAmount();
        convert >> amountString;
        transactions += amountString;
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
        Transaction dummyT = Transaction("", 0, "");
        std::ifstream tFile(fileName.c_str());
        std::string fileContent((std::istreambuf_iterator<char>(tFile)) ,(std::istreambuf_iterator<char>()));
        if(fileContent.length() < 1 )
            return;
       tFile.close(); // Close file and start working on the local string
       for(int i=0; i < fileContent.length(); i++){
           if(fileContent.at(i) != '\n' && fileContent.at(i) != '\r')
               dummyField += fileContent.at(i);
           else{
               if(fieldCount == 1){ dummyT.setReceiver(dummyField); fieldCount += 1; dummyField = "";}
               else if(fieldCount == 2){ dummyT.setToken(dummyField); fieldCount += 1; dummyField = "";}
               else if(fieldCount == 3){ 
                   dummyT.setAmount(atoi(dummyField.c_str())); 
                   fieldCount = 0; 
                   dummyField = "";
                   aTransactions.push_back(dummyT);
               }
               else fieldCount += 1;
           }
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












