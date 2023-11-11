<div class="search-container">
    <h2 class="centered-title">Hae Tunteja</h2>
    <div class="yellow-lines">
        <div class="yellow-line1"></div>
        <div class="yellow-line2"></div>
    </div>
    <div class="dropdown">
        <label>Kaupunki</label>
        <select id="citySelect">
            <option value=""></option> <!-- Lisätty tyhjä rivi -->
            <?php while ($row = $cityResult->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row['city'] ?>"><?= $row['city'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="dropdown">
        <label>Kuntosalin Osoite</label>
        <select id="gymSelect">
            <option value=""></option> <!-- Lisätty tyhjä rivi -->
            <?php while ($row = $addressResult->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row['address'] ?>"><?= $row['address'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <h2 class="centered-title_filters-title">Suodattimet</h2>
    <div class="dropdown">
        <label>Tunnin Nimi</label>
        <select id="classNameSelect">
            <option value=""></option> <!-- Lisätty tyhjä rivi -->
            <?php while ($row = $classNameResult->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="dropdown">
        <label>Ohjaajat</label>
        <select id="instructorSelect">
            <option value=""></option> <!-- Lisätty tyhjä rivi -->
            <?php while ($row = $instructorResult->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row['instructor_id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="time-filter">
        <label>Alkuaika:</label>
        <input type="time" id="startTime">
    </div>
    <div class="time-filter">
        <label>Loppuaika:</label>
        <input type="time" id="endTime">
    </div>
</div>