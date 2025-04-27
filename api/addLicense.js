const { MongoClient, ServerApiVersion } = require('mongodb');

// MongoDB URI (Replace with your environment variable or connection string)
const uri = process.env.MONGODB_URI;

const client = new MongoClient(uri, {
  serverApi: {
    version: ServerApiVersion.v1,
    strict: true,
    deprecationErrors: true,
  }
});

module.exports = async (req, res) => {
    if (req.method === 'POST') {
        const { license } = req.body;

        if (!license) {
            return res.status(400).json({ success: false, message: 'Lisensi tidak boleh kosong' });
        }

        try {
            // Connect to MongoDB
            await client.connect();

            const db = client.db("licensingDB"); // Use your database name
            const collection = db.collection("licenses"); // Use your collection name

            // Insert license into MongoDB
            const result = await collection.insertOne({ license });

            // Return success response
            res.status(200).json({ success: true, message: 'Lisensi berhasil ditambahkan!' });
        } catch (error) {
            console.error('Database connection error:', error);
            res.status(500).json({ success: false, message: 'Terjadi kesalahan di server.' });
        } finally {
            // Close MongoDB connection
            await client.close();
        }
    } else {
        // Handle non-POST requests
        res.status(405).json({ success: false, message: 'Method Not Allowed' });
    }
};
