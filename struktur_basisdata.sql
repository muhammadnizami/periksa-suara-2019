-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: 64.190.202.44    Database: periksa_suara_2019
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.16.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `kecamatan`
--

DROP TABLE IF EXISTS `kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kecamatan` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `id_kecamatan` int(11) NOT NULL,
  `nama_kecamatan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`,`id_kecamatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kelurahan`
--

DROP TABLE IF EXISTS `kelurahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kelurahan` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `id_kecamatan` int(11) NOT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `nama_kelurahan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kotakab`
--

DROP TABLE IF EXISTS `kotakab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kotakab` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `nama_kotakab` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provinsi`
--

DROP TABLE IF EXISTS `provinsi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinsi` (
  `id_provinsi` int(11) NOT NULL,
  `nama_provinsi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_provinsi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suara_kawalpemilu_pilpres`
--

DROP TABLE IF EXISTS `suara_kawalpemilu_pilpres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suara_kawalpemilu_pilpres` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `id_kecamatan` int(11) NOT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `nama_tps` varchar(8) NOT NULL,
  `tanggal_update_suara_kawalpemilu_pilpres` datetime DEFAULT NULL,
  `pas1` int(11) DEFAULT NULL,
  `pas2` int(11) DEFAULT NULL,
  `tSah` int(11) DEFAULT NULL,
  `sah` int(11) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `no_tps` int(11) GENERATED ALWAYS AS (cast(substr(`nama_tps`,4) as signed)) STORED,
  `tipe_form` char(10) NOT NULL,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`,`nama_tps`,`tipe_form`),
  KEY `search_tps_idx` (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`,`no_tps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `suara_situngkpu_pilpres`
--

DROP TABLE IF EXISTS `suara_situngkpu_pilpres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suara_situngkpu_pilpres` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `id_kecamatan` int(11) NOT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `id_tps` int(8) NOT NULL,
  `tanggal_update_suara_situngkpu_pilpres` datetime DEFAULT NULL,
  `pas1` int(11) DEFAULT NULL,
  `pas2` int(11) DEFAULT NULL,
  `tSah` int(11) DEFAULT NULL,
  `sah` int(11) DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`,`id_tps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tps`
--

DROP TABLE IF EXISTS `tps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tps` (
  `id_provinsi` int(11) NOT NULL,
  `id_kotakab` int(11) NOT NULL,
  `id_kecamatan` int(11) NOT NULL,
  `id_kelurahan` int(11) NOT NULL,
  `id_tps` int(11) NOT NULL,
  `nama_tps` varchar(50) DEFAULT NULL,
  `no_tps` int(11) GENERATED ALWAYS AS (cast(substr(`nama_tps`,4) as signed)) STORED,
  PRIMARY KEY (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`,`id_tps`),
  KEY `search_tps_idx` (`id_provinsi`,`id_kotakab`,`id_kecamatan`,`id_kelurahan`,`no_tps`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-28  8:43:59
