from django.db import models
import os

# Create your models here.

class Sample(models.Model):
    
    User_parent=models.ForeignKey('self', on_delete=models.CASCADE,blank=True,null=True,related_name="owner")
    User_Name=models.CharField(max_length=100)
    created_date = models.DateTimeField(auto_now_add=True,blank=True,null=True)
    modified_date = models.DateTimeField(auto_now=True,blank=True,null=True)
    def __str__(self):
        
        return self.User_Name
    
    class Meta:
       ordering = ('-created_date',)


class Sample_Files(models.Model):
    Project_Files=models.FileField(upload_to='Project_Files/%Y/%m/%d',blank=True,null=True)
    created_date = models.DateTimeField(auto_now_add=True,blank=True,null=True)
    modified_date = models.DateTimeField(auto_now=True,blank=True,null=True)
    def __str__(self):
        name , extension = os.path.split(self.Project_Files.name)
        return extension

    def file_size(self):
        value=os.path.getsize(self.Project_Files.path)
        if value < 512000:
            value = value / 1024.0
            ext = 'kb'
        elif value < 4194304000:
            value = value / 1048576.0
            ext = 'mb'
        else:
            value = value / 1073741824.0
            ext = 'gb'
        return '%s %s' % (str(round(value, 2)), ext)
    
    class Meta:
       ordering = ('-created_date',)