'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Handler extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.belongsToMany(models.Animal, { through: 'AnimalHandler' });
    }
  }
  Handler.init({
    name: DataTypes.STRING,
    power: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Handler',
  });
  return Handler;
};
