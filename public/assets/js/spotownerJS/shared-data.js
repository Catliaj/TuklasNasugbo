// Shared Data for Tourist Spots
// This file should be loaded BEFORE home.js and manage-spot.js in index.html

const sharedTouristSpots = [
    {
        id: 1,
        name: 'Sunset Beach Paradise',
        location: 'Coastal Highway, Bay City',
        description: 'Experience the most beautiful sunset views at our pristine beach location. Perfect for families, couples, and solo travelers seeking relaxation and natural beauty.',
        images: [
            'https://images.unsplash.com/photo-1647962431451-d0fdaf1cf21c?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=800&h=500&fit=crop'
        ],
        price: 125,
        maxVisitors: 50,
        openTime: '06:00',
        closeTime: '20:00',
        rating: 4.8,
        reviews: 156,
        bookings: 6,
        revenue: 2750,
        visitors: 22,
        totalVisits: 1247,
        status: 'active',
        amenities: 'Parking, Restrooms, Restaurant, Gift Shop, Beach Chairs',
        highlights: [
            'Stunning sunset views',
            'Crystal clear waters',
            'Family-friendly facilities',
            'On-site restaurant'
        ]
    },
    {
        id: 2,
        name: 'Mountain Peak Resort',
        location: 'Highland Road, Mountain View',
        description: 'Breathtaking mountain views and fresh air await you at our mountain resort. Ideal for hiking enthusiasts and nature lovers.',
        images: [
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=500&fit=crop&sat=-50',
            'https://images.unsplash.com/photo-1454496522488-7a8e488e8606?w=800&h=500&fit=crop'
        ],
        price: 150,
        maxVisitors: 40,
        openTime: '05:00',
        closeTime: '19:00',
        rating: 4.6,
        reviews: 89,
        bookings: 8,
        revenue: 3600,
        visitors: 24,
        totalVisits: 856,
        status: 'active',
        amenities: 'Parking, Restrooms, Café, Viewing Deck, Trail Maps',
        highlights: [
            'Panoramic mountain views',
            'Hiking trails',
            'Cool climate',
            'Photography spots'
        ]
    },
    {
        id: 3,
        name: 'Tropical Garden Oasis',
        location: 'Garden Lane, Green Valley',
        description: 'A lush tropical garden featuring exotic plants, peaceful walking paths, and serene water features. Perfect for relaxation and photography.',
        images: [
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?w=800&h=500&fit=crop',
            'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=800&h=500&fit=crop'
        ],
        price: 100,
        maxVisitors: 60,
        openTime: '07:00',
        closeTime: '18:00',
        rating: 4.7,
        reviews: 124,
        bookings: 4,
        revenue: 1900,
        visitors: 19,
        totalVisits: 1023,
        status: 'active',
        amenities: 'Parking, Restrooms, Garden Café, Gift Shop, Picnic Areas',
        highlights: [
            'Exotic tropical plants',
            'Peaceful atmosphere',
            'Photo-friendly spots',
            'Guided tours available'
        ]
    }
];

// Make it globally available
window.sharedTouristSpots = sharedTouristSpots;