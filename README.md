## Reward management service documentation

### Deployment

#### Prerequisites
You should have installed kubernetes and minikube on your server
#### Steps
- set up env variables in deployment/envs.yaml
- run the command ``kubectl apply -k ./`` to apply deployment resources
- run the command ``minikube service rewards-api --url``, get a **SERVICE_URL** as a result

### Utils

#### Swagger
- open Swagger in the browser using link **SERVICE_URL**/api/documentation 
- click Authorize button
- Put the auth data using format ```Bearer API_TOKEN``` where **API_TOKEN** is env variable from deployment/envs.yaml 

#### Prometheus
- open Prometheus in the browser using link **SERVICE_URL**/prometheus

### Reward structure

- Attribute "details" has json format allowing to store custom data inside
- Attribute "uid" (null by default) is using to point to user of the main platform

### Main platform integration

To integrate with Reward Service the Main platform should have API methods, performing two actions
 
- check user existence by uid 
- update the user's profile to reflect the new reward.


