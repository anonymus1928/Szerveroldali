'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Comment extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      this.belongsTo(models.User, { onDelete: 'cascade' });
      this.belongsTo(models.Ticket, { onDelete: 'cascade' });
    }
  }
  Comment.init({
    text: DataTypes.STRING,
    UserId: DataTypes.INTEGER,
    TicketId: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Comment',
  });
  return Comment;
};