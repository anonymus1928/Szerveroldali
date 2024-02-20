'use strict';
const { Model } = require('sequelize');
const bcrypt = require('bcrypt');
module.exports = (sequelize, DataTypes) => {
    class User extends Model {
        /**
         * Helper method for defining associations.
         * This method is not a part of Sequelize lifecycle.
         * The `models/index` file will call this method automatically.
         */
        static associate(models) {
            this.hasMany(models.Comment);
            this.belongsToMany(models.Ticket, {
                through: 'TicketUser',
            });
        }

        comparePassword(password) {
            return bcrypt.compareSync(password, this.password);
        }

        toJSON() {
            const userData = this.get();
            if (userData.hasOwnProperty('password')) {
                delete userData.password;
            }
            return userData;
        }
    }
    User.init(
        {
            name: DataTypes.STRING,
            email: DataTypes.STRING,
            password: DataTypes.STRING,
            is_admin: DataTypes.BOOLEAN,
        },
        {
            sequelize,
            modelName: 'User',
            hooks: {
                beforeCreate: user => {
                    user.password = bcrypt.hashSync(user.password, bcrypt.genSaltSync(12));
                },
            },
        }
    );
    return User;
};
