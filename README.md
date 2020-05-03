# Recruiting task 

## Background 

##### XXXXXXXX is a digital negotiation SaaS platform which our customers can use for their procurement of products. 
In the first step of the procurement process, a customer can create a Request For Proposal for his product demand and the system will find matching suppliers. The matching suppliers can submit a Proposal. 

In the second step, the customer can accept, or reject submitted Proposals. 

In the third step, the customer can start a negotiation with the accepted Proposals, in which the suppliers can submit bids. 
When the negotiation has finished, the customer can award the best Proposals for his demand. 

###How does the customer evaluate the best proposal? 
Often, the price is not the sole factor to determine the best Proposal. Instead the customer wants to apply Evaluation Criteria to his request and score each Proposal individually. 

To do so, each Request has at least the default ‘Price’ Evaluation Criterion that is added automatically. 

If the Customer adds additional Evaluation Criteria to the Request, he can set a weight (in percent) for each Evaluation Criterion. The weight of all Evaluation Criteria sums up to 100 percent, including the automatically added ‘Price’ Criterion. 

Each new Proposal has its own Evaluation Scoring objects according to the defined Evaluation Criteria. A Request can have a number (1 to n) of Evaluation Criteria and the same number of Evaluation Scoring objects will be created. 

The Customer can score each Proposal by assigning points (1 to 100 points) for each Evaluation Criterion that he defined in the first place. 

### Business case – A big IT company wants to upgrade all developer notebooks 
The development department needs to purchase new notebooks for their developers. Of special importance are the following properties: 

Processor speed (15%), screen resolution (10%), RAM amount (10%), energy star certificate (5%) 

##### The following companies submitted a Proposal. 
Dell – i7, Quadcore 2,3 GHz, full HD, 16 Gb RAM, energy star 100 certified – 2500 € per device
 
Lenovo – i5, Quadcore 2,2 GHz, full HD, 8 GB RAM, energy star 100 certified – 2300 € per device
 
Asus – i7, Quadcore 2,1 GHz, Ultra HD, 8 GB RAM, energy star 80 certified – 2000 € per device

# Implementation

## Result - platform workflow  
1. Register customer and register supplier
2. Login with customer and submit request
3. Login with supplier submit proposal (supplier will view customer's request only if customer choice supplier's category)
4. Login with customer evaluate proposal and accept or decline it (if customer accept proposal the process continue in the opposite case  the process stop here)
5. Login with supplier submit bid (with contract and total price)
6. Login with customer accept or decline bid (if customer decline bid the process continue in the opposite case  the process stop here) 
7. Login with supplier and view results

## Installation
1. git clone git@bitbucket.org:Lubo13/mysupply.git
2. cd mysupply
3. composer install
4. edit .env file and change DATABASE_URL with your respective settings
5. Execute: php bin/console doctrine:database:create
6. Execute: php bin/console doctrine:migration:migrate
7. Execute: php bin/console doctrine:fixtures:load 
8. Go to project in browser