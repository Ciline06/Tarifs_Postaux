# 📦 TARIFS POSTAUX — Application Web PHP / PostgreSQL

## 🚀 Présentation du projet

Ce projet est une application web permettant de gérer un système de **tarifs postaux internationaux**.

Il repose sur une base de données PostgreSQL et simule un système proche d’un service logistique (type La Poste).

---

## 🎯 Objectifs

- Concevoir une base de données relationnelle
- Gérer des données complexes (destinations, envois, tarifs)
- Implémenter des opérations CRUD complètes
- Connecter une application PHP à PostgreSQL
- Structurer un projet web dynamique

---

## 🧱 Base de données

Le projet est composé de 3 tables principales :

### 📍 G02_Destination
- Nom du pays
- Zone tarifaire
- Code pays
- Devise
- Restrictions d’envoi

---

### 🚚 G02_TypeEnvoi
- Type d’envoi (lettre, colis, express…)
- Délai de livraison
- Assurance possible
- Fragilité
- Option tarifaire

---

### 💰 G02_Tarifer
- Liaison entre destination et type d’envoi
- Poids minimum / maximum
- Tarif
- Date de validité

---

## ⚙️ Technologies utilisées

- PHP
- PostgreSQL
- HTML / CSS
- SQL
- Architecture relationnelle (CRUD)

---

## 📊 Fonctionnalités

### Gestion des données
- Affichage des tables
- Ajout d’enregistrements
- Modification de données
- Suppression de données

### Consultation
- Vue détaillée des enregistrements
- Affichage des relations entre tables

---

## 🌍 Données du projet

Le projet contient :
- +20 destinations internationales
- +15 types d’envoi
- Tarifs selon poids et zone
- Simulation réaliste d’un système postal

---

## ▶️ Installation

### 1. Importer la base de données

```bash
psql -U postgres -d tarifs_postaux -f postgres.sql
