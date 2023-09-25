'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Ticket extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.hasMany(models.Comment);
      this.belongsToMany(models.User, {
        through: 'TicketUser',
        as: 'Users',
      });
    }
  }
  Ticket.init({
    title: DataTypes.STRING,
    priority: DataTypes.INTEGER,
    done: DataTypes.BOOLEAN
  }, {
    sequelize,
    modelName: 'Ticket',
  });
  return Ticket;
};