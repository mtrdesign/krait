import { faker } from '@faker-js/faker';

export const createRandomCat = () => {
  return {
    uuid: faker.string.uuid(),
    first_name: faker.person.firstName(),
    last_name: faker.person.lastName(),
    username: faker.internet.username({
      firstName: 'Awesome Cat',
    }),
    breed: faker.animal.cat(),
    email: faker.internet.email(),
    avatar: faker.image.avatar(),
    birthdate: faker.date.birthdate(),
    created_at: faker.date.past(),
  };
};

export const createColumn = ({
  name,
  label,
  hideLabel = false,
  datetime = false,
  sortable = true,
  fixed = false,
  classes = null,
}) => {
  return {
    name,
    label,
    hideLabel,
    datetime,
    sortable,
    fixed,
    classes,
  };
};
