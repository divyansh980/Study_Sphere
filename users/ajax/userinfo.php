<?php

 $userid= $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE userid='$userid'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$class =$row['class'];
$subject =$row['subject'];
$book =$row['book'];
$query1 = "SELECT DISTINCT lesson FROM adddetails WHERE class='$class' AND subject='$subject' AND book='$book'";
$result1 = $conn->query($query1);
$conn->close();
?>
<input class="form-control" type="hidden" id="class" name="class" value="<?php echo $class;?>"required>
<input class="form-control" type="hidden" id="subject" name="subject" value="<?php echo $subject;?>"required>
<input class="form-control" type="hidden" id="book" name="book" value="<?php echo $book?>"required>

<div class="form-group col-md-4">
	<label class="col-form-label">Lessons</label>
	<div>
		<select class="form-control" name="lesson" id="lessonSelect" required>
        <option disabled selected>Please Select Lessons</option>
        <?php
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row1['lesson']) . "'>" . htmlspecialchars($row1['lesson']) . "</option>";
            }
        } else {
            echo "<option disabled>No lesson available</option>";
        }
        ?>
    </select>
	</div>
</div>
