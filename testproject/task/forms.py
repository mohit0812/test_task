from django import forms
from django.forms import ModelForm
from django.contrib.auth.models import *
from task.models import *



class Samplefile(ModelForm):
    def __init__(self, *args, **kwargs):
        super(Samplefile, self).__init__(*args, **kwargs)
        for visible in self.visible_fields():
            visible.field.widget.attrs['class'] = 'form-control'
    
    Project_Files = forms.FileField(required=False,widget=forms.ClearableFileInput(attrs={'multiple': True}))
    class Meta:
        model = Sample_Files
        # exclude=['project','file_uploade_by']
        fields = ('__all__')