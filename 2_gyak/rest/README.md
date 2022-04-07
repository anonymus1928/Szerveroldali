npx sequelize model:generate --name User --attributes email:string,password:string,isAdmin:boolean

npx sequelize model:generate --name Ticket --attributes title:string,priority:integer,done:boolean

npx sequelize model:generate --name Comment --attributes text:string,UserId:integer,TicketId:integer
