/* Insert values into table user */
INSERT INTO user (firstname, lastname, email, password, roles)
VALUES
 ('Patrick', 'Barros', 'patrick.barros@hypnos.com', 'patpat', 'ROLE_ADMIN'),
 ('Jonathan', 'Pierrot', 'jonathan.pierrot@hypnos.com', 'joejoe', 'ROLE_MANAGER'),
 ('Thomas', 'Carpentier', 'thomas.carpentier@hypnos.com', 'toto', 'ROLE_MANAGER'),
 ('Isabelle', 'Durand', 'isabelle.durand@yahoo.fr', 'isabelledu75', 'ROLE_USER');

/* Insert values into table hotel */ 
INSERT INTO hotel (name, description, address, city, slug, owner_id)
   VALUES
 ('Victoria Palace Hotel',
  'Le Victoria Palace Hotel se situe à 250 mètres de la rue du Cherche-Midi datant du XVIIIème siècle.',
  '6 Rue Blaise Desgoffe, 6e arr., 75006 Paris, France',
  'Paris',
  'victoria-palace-hotel',
  2),
 
 ('Hotel West-End',
  'Cet hôtel de caractère de luxe se situe en plein cœur de Paris.',
  '7 rue Clément Marot, 8e arr., 75008 Paris, France',
  'Paris',
  'hotel-west-end',
  3);

/* Insert values into table image */
INSERT INTO image (name)
   VALUES
 ('image1'),
  ('image2'),
  ('image3');

/* Insert values into table  suite */
INSERT INTO suite (hotel_id, title, description, booking_link, price, featured_image_id)
   VALUES
 (1,
  'Chambre Familiale Supérieure',
  'Cette chambre familiale insonorisée dispose.',
  'https://www.booking.com/hotel/fr/victoria-palace.fr.html',
  13500,
  1),
 
 (2,
  'Chambre Double Standard',
  'Cette chambre est équipée .',
  'https://www.booking.com/hotel/fr/hotelwestend.fr.html',
  12500,
  2), 
  
 (2,
  'Suite Junior',
  'Cette suite dispose.',
  'https://www.booking.com/hotel/fr/hotelwestend.fr.html',
  32400,
  3);


/* Insertion table booking */             
INSERT INTO booking (hotel_id, suite_id, user_id, begin_at, end_at, total_price, status)
   VALUES					 
 (1,
  1,
  4,
  '2020-02-13',
  '2020-02-15',
  27000,
  'accepted'), 	

 (1,
  1,
  4,
  '2021-05-20',
  '2021-05-22',
  27000,
  'cancelled'), 	  
  
  (2,
  1,
  4,
  '2021-12-24',
  '2021-12-25',
  32400,
  'declined'); 
  