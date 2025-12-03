import mysql.connector
import json

# KONEKSI DATABASE
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="db_sewabaju"
)

cursor = db.cursor()

# ======================
#  TOP 5 BAJU PALING DISEWA
# ======================
cursor.execute("""
    SELECT baju.NamaBaju, COUNT(booking.kode_booking) AS total
    FROM booking
    JOIN baju ON booking.id_baju = baju.id_baju
    GROUP BY baju.NamaBaju
    ORDER BY total DESC
    LIMIT 5;
""")
top_baju = cursor.fetchall()

# ======================
#  GRAFIK PENYEWAAN PER BULAN
# ======================
cursor.execute("""
    SELECT DATE_FORMAT(tanggal_booking, '%Y-%m') AS bulan,
           COUNT(*) AS total
    FROM booking
    GROUP BY DATE_FORMAT(tanggal_booking, '%Y-%m')
    ORDER BY bulan ASC;
""")
per_bulan = cursor.fetchall()

# BENTUKKAN JSON
data = {
    "top_baju": [{"nama": row[0], "total": row[1]} for row in top_baju],
    "per_bulan": [{"bulan": row[0], "total": row[1]} for row in per_bulan]
}

print(json.dumps(data))
