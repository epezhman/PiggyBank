#include <iostream>
#include <fstream>
#include <istream>
#include <string.h>
#include <stdlib.h>

/////////////////////////////////////////
// Transaction data should contain     //
//-------------------------------------//
// Transaction receiver: varchar(10)   //
// Transaction amount: double          //
// Transaction token/TAN: varchar(15)  //
/////////////////////////////////////////

class Transaction{
    // A class to hold the transaction data read from a file
    // Private by default
//    std::string transactionSender;
    std::string transactionReceiver;
    double transactionAmount;
    std::string transactionToken;
    
    public:
        Transaction (std::string, double, std::string);
//        void setSender(std::string tSender);
        void setReceiver(std::string tReceiver);
        void setAmount(double tAmount);
        void setToken(std::string tToken);
//        std::string getSender();
        std::string getReceiver();
        double getAmount();
        std::string getToken();
};

Transaction::Transaction(std::string tReceiver, double tAmount, std::string tToken){
//    this->transactionSender = tSender;
    this->transactionReceiver = tReceiver;
    this->transactionAmount = tAmount;
    this->transactionToken = tToken;
}
//void Transaction::setSender(std::string tSender){ this->transactionSender = tSender; }
void Transaction::setReceiver(std::string tReceiver){ this->transactionReceiver = tReceiver; }
void Transaction::setAmount(double tAmount){ this->transactionAmount = tAmount; }
void Transaction::setToken(std::string tToken){ this->transactionToken = tToken; }

//std::string Transaction::getSender(){ return this->transactionSender; }
std::string Transaction::getReceiver(){ return this->transactionReceiver; }
double Transaction::getAmount(){ return this->transactionAmount; }
std::string Transaction::getToken(){ return this->transactionToken; }

void parseTransactionFile(Transaction* t, std::string fileName){
    std::ifstream tFile;
    try{
        // Open the file 
        tFile.open(fileName.c_str(), std::ifstream::in);
        char* c = new char [1];
        std::string currentField = "";
        int fieldCount = 1; // Expected to be only 3
        tFile.read(c, 1); // Read a character from the file
        while(c[0] != EOF && fieldCount < 4){  
            while(c[0] != '\n'){
                currentField += c[0];
                tFile.read(c, 1);
            }
            switch(fieldCount){
                case 1:
                    t->setReceiver(currentField);
                    break;
                case 2:
                    t->setToken(currentField);
                    break;
                case 3:
                    t->setAmount(atof(currentField.c_str())); // Cannot use std::stod to support older compilers
                    break;
//                case 4:
//                    t->setToken(currentField);
//                    break;
            };
            currentField = "";
            fieldCount += 1;
            tFile.read(c, 1);
        }
   }catch(std::exception& e){
       std::cout << "Error encountered: " << e.what() << std::endl;
       tFile.close();
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
       
        Transaction currentTransaction ("", 0, ""); // Initialize a dummy transaction instant
        parseTransactionFile(&currentTransaction, fileName); // .. and pass it [by reference] for parsing
       
        std::cout <<  currentTransaction.getReceiver() << ":" << currentTransaction.getToken() << ":" << currentTransaction.getAmount();
        return 1;
   }catch(std::exception& e){
       std::cout << "Error encountered: " << e.what() << std::endl;
       return -1;
   }
}












