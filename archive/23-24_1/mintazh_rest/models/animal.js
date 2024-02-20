'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Animal extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.belongsTo(models.Place);
      this.belongsToMany(models.Handler, { through: 'AnimalHandler' });
    }
  }
  Animal.init({
    name: DataTypes.STRING,
    weight: DataTypes.FLOAT,
    birthdate: DataTypes.DATE,
    image: DataTypes.STRING,
    PlaceId: DataTypes.INTEGER
  }, {
    sequelize,
    modelName: 'Animal',
  });
  return Animal;
};
