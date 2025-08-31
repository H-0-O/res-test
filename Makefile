up:
	docker compose --env-file=./backend/.env --env-file=.env up -d
down:
	docker compose --env-file=./backend/.env --env-file=.env down
	
fix-log: 
	docker exec -it reservation-test-app chown -R www-data:www-data storage
